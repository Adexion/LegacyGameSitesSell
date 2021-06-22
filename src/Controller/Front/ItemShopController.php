<?php

namespace MNGame\Controller\Front;

use Exception;
use ReflectionException;
use Doctrine\ORM\ORMException;
use MNGame\Enum\PaymentTypeEnum;
use MNGame\Database\Entity\Wallet;
use MNGame\Service\ServerProvider;
use MNGame\Enum\PaymentStatusEnum;
use MNGame\Database\Entity\ItemList;
use MNGame\Exception\ContentException;
use MNGame\Service\User\WalletService;
use MNGame\Database\Entity\Configuration;
use MNGame\Enum\PaymentConfigurationType;
use Doctrine\ORM\OptimisticLockException;
use MNGame\Database\Entity\PaymentHistory;
use MNGame\Service\Payment\PaymentService;
use Symfony\Component\HttpFoundation\Request;
use MNGame\Service\Payment\PaymentFormFactory;
use Symfony\Component\HttpFoundation\Response;
use MNGame\Exception\ItemListNotFoundException;
use Symfony\Component\Routing\Annotation\Route;
use MNGame\Exception\PaymentProcessingException;
use MNGame\Service\Minecraft\ExecuteItemService;
use MNGame\Service\Payment\AcceptPaymentService;
use MNGame\Database\Repository\PaymentRepository;
use MNGame\Database\Repository\ItemListRepository;
use MNGame\Database\Repository\PaymentHistoryRepository;

class ItemShopController extends AbstractController
{
    /**
     * @Route(name="item-shop", path="/itemshop")
     */
    public function itemShop(ServerProvider $serverProvider, PaymentHistoryRepository $repository): Response
    {
        $server = $serverProvider->getSessionServer();

        return $this->render('base/page/itemshop.html.twig', [
            'serverName' => $server->getName(),
            'wallet'     => $this->getDoctrine()->getRepository(Wallet::class)->findOneBy(['user' => $this->getUser()]),
            'amount'     => $repository->getThisMonthMoney(),
            'itemLists'  => $this->getDoctrine()->getRepository(ItemList::class)->findBy(['serverId' => $server->getId()]),
        ]);
    }

    /**
     * @Route(name="item-shop-form", path="/item/form/{itemId}")
     */
    public function itemFormList(PaymentFormFactory $formFactory, string $itemId): Response
    {
        return $this->render('base/page/itemshopItemForm.html.twig', [
            '$itemList',
            'formList' => $formFactory->create(uniqid(), $itemId),
        ]);
    }

    /**
     * @Route(name="prepaid-status", path="/prepaid/status")
     * @throws ContentException
     * @throws ReflectionException
     */
    public function prepaidStatus(Request $request, ItemListRepository $itemListRepository, WalletService $walletService, ExecuteItemService $executeItemService): Response
    {
        /** @var ItemList $itemList */
        $itemList = $itemListRepository->find($request->request->getInt('itemListId'));

        try {
            $code = $executeItemService->executeItemListInstant(
                $walletService->changeCash(0, $this->getUser()),
                $request->request->getInt('itemListId') ?? 0,
                $this->getUser(),
                true
            );
        } catch (PaymentProcessingException) {
            $code = Response::HTTP_PAYMENT_REQUIRED;
        } catch (ItemListNotFoundException) {
            $code = Response::HTTP_OK;
        }

        return $this->render('base/page/payment.html.twig', [
            'itemList'     => $itemList,
            'responseType' => $code,
            'wallet'       => $this->getDoctrine()->getRepository(Wallet::class)->findOneBy(['user' => $this->getUser()]),
        ]);
    }

    /**
     * @Route(name="payment-status", path="/payment/status")
     * @throws ContentException
     * @throws ReflectionException
     * @throws Exception
     */
    public function paymentStatus(Request $request, PaymentService $paymentService, WalletService $walletService, ServerProvider $serverProvider): Response
    {
        $paymentType    = new PaymentTypeEnum($request->request->get('paymentType'));
        $paymentHistory = $this->getDoctrine()->getRepository(PaymentHistory::class)->findOneBy([
            'paymentType' => $paymentType->getKey(),
            'paymentId'   => $request->request->get('paymentId') ?? 0,
        ]);

        if ($paymentHistory instanceof PaymentHistory && $paymentHistory->getPaymentStatus() === PaymentStatusEnum::SUCCESS) {
            return $this->render('base/page/payment.html.twig', [
                'responseType' => Response::HTTP_TOO_MANY_REQUESTS,
                'wallet'       => $this->getDoctrine()->getRepository(Wallet::class)->findOneBy(['user' => $this->getUser()]),
            ]);
        }

        $payment = $serverProvider->getSessionServer()->getPaymentByType($paymentType);

        $amount = $paymentService->executePayment($request->request->all(), $this->getUser()->getUsername(), $payment);
        if ($request->request->get('status') === PaymentStatusEnum::SUCCESS) {
            $walletService->changeCash($amount, $this->getUser());
        }

        return $this->render('base/page/payment.html.twig', [
            'responseType' => Response::HTTP_OK,
            'wallet'       => $this->getDoctrine()->getRepository(Wallet::class)->findOneBy(['user' => $this->getUser()]),
        ]);
    }

    /**
     * @Route(name="paymentAccept", path="/payment/{paymentType}")
     *
     * @throws ContentException
     * @throws ReflectionException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function paymentAccept(Request $request, string $paymentType, ExecuteItemService $executeItemService, WalletService $walletService, AcceptPaymentService $acceptPaymentService): Response
    {
        if ($request->request->get('STATUS') !== PaymentStatusEnum::SUCCESS && $request->request->get('status') !== PaymentStatusEnum::SUCCESS) {
            return $this->render('base/page/payment.html.twig', [
                'responseType' => Response::HTTP_PAYMENT_REQUIRED
            ]);
        }

        $paymentHistory = $acceptPaymentService->accept($paymentType, $request->request->all());
        return $this->render('base/page/payment.html.twig', [
            'responseType' => $executeItemService->executeItem($paymentHistory->getUser(), $paymentHistory->getItemList()->getId()),
            'wallet' => $walletService->changeCash(0, $paymentHistory->getUser()),
            'itemList' => $paymentHistory->getItemList()
        ]);
    }
}
