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
use Doctrine\ORM\OptimisticLockException;
use Symfony\Component\HttpFoundation\Request;
use MNGame\Service\Payment\PaymentFormFactory;
use Symfony\Component\HttpFoundation\Response;
use MNGame\Exception\ItemListNotFoundException;
use Symfony\Component\Routing\Annotation\Route;
use MNGame\Exception\PaymentProcessingException;
use MNGame\Service\Minecraft\ExecuteItemService;
use MNGame\Service\Payment\AcceptPaymentService;
use MNGame\Database\Repository\ItemListRepository;
use MNGame\Database\Repository\PaymentHistoryRepository;
use MNGame\Database\Repository\ItemListStatisticRepository;

class ItemShopController extends AbstractController
{
    /**
     * @Route(name="item-shop", path="/itemshop")
     */
    public function itemShop(ServerProvider $serverProvider, PaymentHistoryRepository $paymentHistoryRepository, ItemListStatisticRepository $listStatisticRepository): Response
    {
        $server = $serverProvider->getSessionServer();

        return $this->render('base/page/itemshop.html.twig', [
            'serverName'    => $server->getName(),
            'wallet'        => $this->getDoctrine()->getRepository(Wallet::class)->findOneBy(['user' => $this->getUser()]),
            'amount'        => $paymentHistoryRepository->getThisMonthMoney(),
            'lastBuyerList' => $listStatisticRepository->getStatistic(),
            'itemLists'     => $this->getDoctrine()->getRepository(ItemList::class)->findBy(['serverId' => $server->getId()]),
        ]);
    }

    /**
     * @Route(name="item-shop-form", path="/item/form/{itemId}")
     */
    public function itemFormList(PaymentFormFactory $formFactory, string $itemId, ItemListRepository $itemListRepository): Response
    {
        return $this->render('base/page/itemshopItemForm.html.twig', [
            'itemList' => $itemListRepository->find($itemId),
            'formList' => $formFactory->create('GS' . date('YmdHis') . $itemId, $itemId),
        ]);
    }

    /**
     * @Route(name="prepaid-buy", path="/prepaid/buy")
     * @throws ContentException
     * @throws ReflectionException
     */
    public function prepaidBuy(Request $request, ItemListRepository $itemListRepository, WalletService $walletService, ExecuteItemService $executeItemService): Response
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
     * @Route(name="wallet-page", path="/prepaid")
     *
     * @throws OptimisticLockException
     * @throws ORMException
     * @throws ReflectionException
     */
    public function wallet(Request $request ,PaymentFormFactory $formFactory): Response
    {
        return $this->render('base/page/wallet.html.twig', [
            'formList' => $formFactory->createForm('GS' . date('YmdHis'), $request->request->get('price', 1) ?: 1),
            'wallet'   => $this->getDoctrine()->getRepository(Wallet::class)->findOneBy(['user' => $this->getUser()]),
        ]);
    }

    /**
     * @Route(name="prepaid-status", path="/prepaid/status")
     *
     * @throws ContentException
     * @throws ReflectionException
     * @throws Exception
     */
    public function prepaidStatus(Request $request, WalletService $walletService, AcceptPaymentService $acceptPaymentService): Response
    {
        if ($request->request->get('STATUS') !== PaymentStatusEnum::SUCCESS && $request->request->get('status', PaymentStatusEnum::SUCCESS) !== PaymentStatusEnum::SUCCESS) {
            return $this->render('base/page/payment.html.twig', [
                'responseType' => Response::HTTP_PAYMENT_REQUIRED,
            ]);
        }

        try {
            $paymentHistory = $acceptPaymentService->accept(PaymentTypeEnum::PREPAID, $request->request->all());
        } catch (PaymentProcessingException) {
            return $this->render('base/page/payment.html.twig', [
                'responseType' => Response::HTTP_PAYMENT_REQUIRED,
            ]);
        }

        $walletService->changeCash($paymentHistory->getAmount(), $this->getUser());

        return $this->render('base/page/payment.html.twig', [
            'responseType' => Response::HTTP_OK
        ]);
    }

    /**
     * @Route(name="payment-accept", path="/payment/{paymentType}")
     *
     * @throws ContentException
     * @throws ReflectionException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function paymentAccept(Request $request, string $paymentType, ExecuteItemService $executeItemService, WalletService $walletService, AcceptPaymentService $acceptPaymentService): Response
    {
        if ($request->request->get('STATUS') !== PaymentStatusEnum::SUCCESS && $request->request->get('status', PaymentStatusEnum::SUCCESS) !== PaymentStatusEnum::SUCCESS) {
            return $this->render('base/page/payment.html.twig', [
                'responseType' => Response::HTTP_PAYMENT_REQUIRED,
            ]);
        }

        try {
            $paymentHistory = $acceptPaymentService->accept($paymentType, $request->request->all());
        } catch (PaymentProcessingException) {
            return $this->render('base/page/payment.html.twig', [
                'responseType' => Response::HTTP_PAYMENT_REQUIRED,
            ]);
        }

        return $this->render('base/page/payment.html.twig', [
            'responseType' => $executeItemService->executeItem($paymentHistory->getUser(), $paymentHistory->getItemList()->getId()),
            'itemList'     => $paymentHistory->getItemList(),
        ]);
    }
}
