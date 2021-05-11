<?php

namespace MNGame\Controller\Front;

use GuzzleHttp\Exception\GuzzleException;
use MNGame\Database\Entity\ItemList;
use MNGame\Database\Entity\Payment;
use MNGame\Database\Entity\PaymentHistory;
use MNGame\Database\Entity\PaySafeCard;
use MNGame\Database\Entity\Wallet;
use MNGame\Database\Repository\ItemListRepository;
use MNGame\Database\Repository\PaymentHistoryRepository;
use MNGame\Enum\PaymentTypeEnum;
use MNGame\Exception\ContentException;
use MNGame\Exception\ItemListNotFoundException;
use MNGame\Exception\PaymentProcessingException;
use MNGame\Service\Connection\Minecraft\ExecuteItemService;
use MNGame\Service\Connection\Payment\PaymentService;
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
    public function itemShop(ServerProvider $serverProvider, PaymentHistoryRepository $repository): Response
    {
        $server = $serverProvider->getSessionServer();

        return $this->render('base/page/itemshop.html.twig', [
            'serverName' => $server->getName(),
            'wallet' => $this->getDoctrine()->getRepository(Wallet::class)->findOneBy(['user' => $this->getUser()]),
            'amount' => $repository->getThisMonthMoney(),
            'itemLists' => $this->getDoctrine()->getRepository(ItemList::class)->findBy(['serverId' => $server->getId()]),
        ]);
    }

    public function formList(ServerProvider $serverProvider, Request $request) {
        /** @var Payment $payment */
        foreach ($serverProvider->getSessionServer()->getPayments() as $payment) {

        }

        return $this->render('base/page/itemshopItemForm.html.twig', [
            'serverName' => $server->getName(),
            'wallet' => $this->getDoctrine()->getRepository(Wallet::class)->findOneBy(['user' => $this->getUser()]),
            'amount' => $repository->getThisMonthMoney(),
            'itemLists' => $this->getDoctrine()->getRepository(ItemList::class)->findBy(['serverId' => $server->getId()]),
        ]);
    }

    /**
     * @Route(name="prepaid-status", path="/prepaid/status")
     *
     * @throws ContentException
     * @throws ReflectionException
     */
    public function prepaidStatus(
        Request $request,
        ItemListRepository $itemListRepository,
        WalletService $walletService,
        ExecuteItemService $executeItemService
    ): Response {
        /** @var ItemList $itemList */
        $itemList = $itemListRepository->find($request->request->getInt('itemListId'));

        try {
            $code = $executeItemService->executeItemListInstant(
                $walletService->changeCash(0, $this->getUser()),
                $request->request->getInt('itemListId') ?? 0,
                $this->getUser(),
                true
            );
        } catch (PaymentProcessingException $e) {
            $code = Response::HTTP_PAYMENT_REQUIRED;
        } catch (ItemListNotFoundException $e) {
            $code = Response::HTTP_OK;
        }

        return $this->render('base/page/payment.html.twig', [
            'itemList' => $itemList,
            'responseType' => $code,
            'wallet' => $this->getDoctrine()->getRepository(Wallet::class)->findOneBy(['user' => $this->getUser()]),
        ]);
    }

    /**
     * @Route(name="payment-status", path="/payment/status")
     *
     * @throws GuzzleException
     * @throws ContentException
     * @throws ReflectionException
     */
    public function paymentStatus(Request $request, PaymentService $paymentService, WalletService $walletService, ServerProvider $serverProvider): Response
    {
        $paymentType = new PaymentTypeEnum($request->request->get('paymentType'));
        $paymentHistory = $this->getDoctrine()->getRepository(PaymentHistory::class)->findOneBy(
            [
                'paymentType' => $paymentType->getKey(),
                'paymentId' => $request->request->get('paymentId') ?? 0,
            ]
        );

        if ($paymentHistory instanceof PaymentHistory) {
            return $this->render('base/page/payment.html.twig', [
                'responseType' => Response::HTTP_TOO_MANY_REQUESTS,
                'wallet' => $this->getDoctrine()->getRepository(Wallet::class)->findOneBy(['user' => $this->getUser()]),
            ]);
        }

        $payment = $serverProvider->getSessionServer()->getPaymentByType($paymentType);

        $amount = $paymentService->executePayment($request->request->all(), $this->getUser()->getUsername(), $payment);
        $walletService->changeCash($amount, $this->getUser());

        return $this->render('base/page/payment.html.twig', [
            'responseType' => HTTP::OK,
            'wallet' => $this->getDoctrine()->getRepository(Wallet::class)->findOneBy(['user' => $this->getUser()]),
        ]);
    }
}
