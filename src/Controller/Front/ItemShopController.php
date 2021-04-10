<?php

namespace MNGame\Controller\Front;

use GuzzleHttp\Exception\GuzzleException;
use MNGame\Database\Entity\ItemList;
use MNGame\Database\Entity\PaymentHistory;
use MNGame\Database\Entity\PaySafeCard;
use MNGame\Database\Entity\User;
use MNGame\Database\Entity\Wallet;
use MNGame\Database\Repository\ItemListRepository;
use MNGame\Exception\ContentException;
use MNGame\Exception\ItemListNotFoundException;
use MNGame\Exception\PaymentProcessingException;
use MNGame\Service\Connection\Minecraft\ExecuteItemService;
use MNGame\Service\Connection\Payment\PayPal\PayPalService;
use MNGame\Service\Mail\MailSenderService;
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
    public function itemShop(ServerProvider $serverProvider): Response
    {
        $server = $serverProvider->getSessionServer();

        return $this->render('base/page/itemshop.html.twig', [
            'itemLists' => $this->getDoctrine()->getRepository(ItemList::class)->findBy(['serverId' => $server->getId()]),
            'paypalClient' => $server->getPaypal()->getClient(),
            'wallet' => $this->getDoctrine()->getRepository(Wallet::class)->findOneBy(['user' => $this->getUser()]),
        ]);
    }

    /**
     * @Route(name="prepaid-status", path="/prepaid/status")
     *
     * @throws ContentException
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
     * @Route(name="paypal-status", path="/paypal/status")
     *
     * @throws ContentException
     * @throws PaymentProcessingException
     * @throws GuzzleException
     * @throws ReflectionException
     */
    public function paypalStatus(
        Request $request,
        ItemListRepository $itemListRepository,
        PayPalService $payment,
        ExecuteItemService $executeItemService
    ): Response {
        $paymentHistory = $this->getDoctrine()->getRepository(PaymentHistory::class)->findOneBy(
            [
                'paymentType' => 'paypal',
                'paymentId' => $request->request->get('orderId') ?? 0,
            ]
        );

        if ($paymentHistory instanceof PaymentHistory) {
            return $this->render('base/page/payment.html.twig', [
                'itemList' => [],
                'responseType' => Response::HTTP_TOO_MANY_REQUESTS,
                'wallet' => $this->getDoctrine()->getRepository(Wallet::class)->findOneBy(['user' => $this->getUser()]),
            ]);
        }

        $amount = $payment->executePayment($request->request->get('orderId') ?? '0', $this->getUser()->getUsername());
        $itemList = $itemListRepository->find($request->request->getInt('itemListId'));

        try {
            $code = $executeItemService->executeItemListInstant(
                $amount,
                $request->request->getInt('itemListId') ?? 0,
                $this->getUser()
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
     * @Route(name="paySafeCard-status", path="/paySafeCard/status")
     */
    public function paySafeCardStatus(Request $request, MailSenderService $mailSenderService): Response
    {
        if (empty($request->request->get('code'))) {
            return $this->render('base/page/paySafeCard.html.twig', [
                'responseType' => Response::HTTP_BAD_REQUEST,
                'wallet' => $this->getDoctrine()->getRepository(Wallet::class)->findOneBy(['user' => $this->getUser()]),
            ]);
        }

        /** @var User $user */
        $user = $this->getUser();
        $paySafeCard = new PaySafeCard();

        $paySafeCard->setUser($user);
        $paySafeCard->setCode($request->request->get('code'));
        $paySafeCard->setMoney((float)$request->request->get('money'));

        $dm = $this->getDoctrine()->getManager();
        $dm->persist($paySafeCard);
        $dm->flush();

        $mailSenderService->sendEmailBySchema(
            Response::HTTP_PAYMENT_REQUIRED,
            [$this->getUser()->getUsername(), $request->request->get('code')]
        );

        return $this->render('base/page/paySafeCard.html.twig', [
            'responseType' => Response::HTTP_OK,
            'wallet' => $this->getDoctrine()->getRepository(Wallet::class)->findOneBy(['user' => $this->getUser()]),
        ]);
    }
}
