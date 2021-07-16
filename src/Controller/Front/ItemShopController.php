<?php

namespace MNGame\Controller\Front;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use MNGame\Database\Entity\ItemList;
use MNGame\Database\Entity\Wallet;
use MNGame\Database\Repository\ItemListRepository;
use MNGame\Database\Repository\ItemListStatisticRepository;
use MNGame\Database\Repository\PaymentHistoryRepository;
use MNGame\Enum\PaymentTypeEnum;
use MNGame\Exception\ContentException;
use MNGame\Exception\ItemListNotFoundException;
use MNGame\Exception\PaymentProcessingException;
use MNGame\Service\Minecraft\ExecuteItemService;
use MNGame\Service\Payment\PaymentAcceptor;
use MNGame\Service\Payment\PaymentFormFactory;
use MNGame\Service\ServerProvider;
use MNGame\Service\User\WalletService;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ItemShopController extends AbstractController
{
    /**
     * @Route(name="item-shop", path="/itemshop")
     */
    public function itemShop(ServerProvider $serverProvider, PaymentHistoryRepository $paymentHistoryRepository, ItemListStatisticRepository $listStatisticRepository): Response
    {
        $server = $serverProvider->getSessionServer();

        return $this->render('base/page/itemshop.html.twig', [
            'serverName' => $server->getName(),
            'wallet' => $this->getDoctrine()->getRepository(Wallet::class)->findOneBy(['user' => $this->getUser()]),
            'amount' => $paymentHistoryRepository->getThisMonthMoney(),
            'lastBuyerList' => $listStatisticRepository->getStatistic(),
            'itemLists' => $this->getDoctrine()->getRepository(ItemList::class)->findBy(['serverId' => $server->getId()]),
        ]);
    }

    /**
     * @Route(name="item-shop-form", path="/item/form/{itemId}")
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ReflectionException
     */
    public function itemFormList(PaymentFormFactory $formFactory, string $itemId, ItemListRepository $itemListRepository): Response
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('login');
        }

        return $this->render('base/page/itemshopItemForm.html.twig', [
            'itemList' => $itemListRepository->find($itemId),
            'formList' => $formFactory->create('GS'.date('YmdHis'), $itemId),
        ]);
    }

    /**
     * @Route(name="wallet-page", path="/prepaid")
     *
     * @throws OptimisticLockException
     * @throws ORMException
     * @throws ReflectionException
     */
    public function wallet(Request $request, PaymentFormFactory $formFactory): Response
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('login');
        }

        return $this->render('base/page/wallet.html.twig', [
            'formList' => $formFactory->createFormList('GS'.date('YmdHis'), $request->request->get('price', 1) ?: 1),
            'wallet' => $this->getDoctrine()->getRepository(Wallet::class)->findOneBy(['user' => $this->getUser()]),
        ]);
    }

    /**
     * @Route(name="prepaid-status", path="/prepaid/payment/{paymentType}")
     *
     * @throws ContentException
     * @throws ReflectionException
     * @throws Exception
     */
    public function prepaidStatus(Request $request, string $paymentType, WalletService $walletService, PaymentAcceptor $paymentAcceptor): Response
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('login');
        }

        try {
            $paymentHistory = $paymentAcceptor->accept($request, PaymentTypeEnum::getValueByCamelCaseKey(ucfirst($paymentType)));
        } catch (PaymentProcessingException) {
            return $this->render('base/page/payment.html.twig', [
                'responseType' => Response::HTTP_PAYMENT_REQUIRED,
            ]);
        }

        $walletService->changeCash($paymentHistory->getAmount(), $this->getUser());

        return $this->render('base/page/payment.html.twig', [
            'responseType' => Response::HTTP_OK,
        ]);
    }

    /**
     * @Route(name="payment-accept", path="/payment/{paymentType}")
     *
     * @throws ContentException
     * @throws ItemListNotFoundException
     * @throws PaymentProcessingException
     * @throws ReflectionException
     * @throws ORMException
     */
    public function paymentAccept(Request $request, string $paymentType, ExecuteItemService $executeItemService, PaymentAcceptor $paymentAcceptor): Response
    {
        try {
            $paymentHistory = $paymentAcceptor->accept($request, PaymentTypeEnum::getValueByCamelCaseKey(ucfirst($paymentType)));
        } catch (PaymentProcessingException) {
            return $this->render('base/page/payment.html.twig', [
                'responseType' => Response::HTTP_PAYMENT_REQUIRED,
            ]);
        }

        return $this->render('base/page/payment.html.twig', [
            'responseType' => $executeItemService->executeItemListInstant($paymentHistory->getAmount(), $paymentHistory->getItemList()->getId(), $this->getUser(), PaymentTypeEnum::getValueByCamelCaseKey(ucfirst($paymentType)) === PaymentTypeEnum::PREPAID),
            'itemList' => $paymentHistory->getItemList(),
        ]);
    }
}
