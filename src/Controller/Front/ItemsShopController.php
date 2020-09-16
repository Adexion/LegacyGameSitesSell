<?php

namespace ModernGame\Controller\Front;

use ModernGame\Database\Entity\ItemList;
use ModernGame\Database\Entity\PaySafeCard;
use ModernGame\Database\Entity\User;
use ModernGame\Database\Entity\Wallet;
use ModernGame\Database\Repository\ItemListRepository;
use ModernGame\Service\Connection\Minecraft\RCONService;
use ModernGame\Service\Connection\Payment\PayPal\PayPalService;
use ModernGame\Service\Mail\MailSenderService;
use ModernGame\Service\User\WalletService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ItemsShopController extends AbstractController
{
    /**
     * @Route(name="item-shop", path="/itemshop")
     */
    public function itemShop()
    {
        return $this->render('front/page/itemshop.html.twig', [
            'itemLists' => $this->getDoctrine()->getRepository(ItemList::class)->findAll(),
            'paypalClient' => $this->getParameter('paypal')['client'],
            'wallet' => $this->getDoctrine()->getRepository(Wallet::class)->findOneBy(['user' => $this->getUser()])
        ]);
    }

    /**
     * @Route(name="prepaid-status", path="/prepaid/status")
     */
    public function prepaidStatus(
        Request $request,
        ItemListRepository $itemListRepository,
        WalletService $walletService,
        RCONService $rcon
    ) {
        /** @var ItemList $itemList */
        $itemList = $itemListRepository->find($request->request->getInt('itemListId'));

        $code = $rcon->executeItemListInstant(
            $walletService->changeCash(0, $this->getUser()),
            $request->request->getInt('itemListId') ?? 0,
            $this->getUser()
        );

        if ($code !== Response::HTTP_PAYMENT_REQUIRED) {
            $walletService->changeCash(-$itemList->getAfterPromotionPrice(), $this->getUser());
        }

        return $this->render('front/page/payment.html.twig', [
            'itemList' => $itemList,
            'responseType' => $code,
            'wallet' => $this->getDoctrine()->getRepository(Wallet::class)->findOneBy(['user' => $this->getUser()]),
        ]);
    }

    /**
     * @Route(name="paypal-status", path="/paypal/status")
     */
    public function paypalStatus(
        Request $request,
        ItemListRepository $itemListRepository,
        PayPalService $payment,
        RCONService $rcon
    ) {
        $amount = $payment->executePayment($request->request->get('orderId') ?? 0, $this->getUser()->getUsername());
        $itemList = $itemListRepository->find($request->request->getInt('itemListId'));

        $code = $rcon->executeItemListInstant(
            $amount,
            $request->request->getInt('itemListId') ?? 0,
            $this->getUser()
        );

        return $this->render('front/page/payment.html.twig', [
            'itemList' => $itemList,
            'responseType' => $code,
            'wallet' => $this->getDoctrine()->getRepository(Wallet::class)->findOneBy(['user' => $this->getUser()]),
        ]);
    }

    /**
     * @Route(name="paySafeCard-status", path="/paySafeCard/status")
     */
    public function paySafeCardStatus(Request $request, MailSenderService $mailSenderService)
    {
        if (empty($request->request->get('code'))) {
            return $this->render('front/page/paySafeCard.html.twig', [
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

        $mailSenderService->sendEmail(
            Response::HTTP_PAYMENT_REQUIRED,
            [$this->getUser()->getUsername(), $request->request->get('code')],
            'moderngameservice@gmail.com'
        );

        return $this->render('front/page/paySafeCard.html.twig', [
            'responseType' => Response::HTTP_OK,
            'wallet' => $this->getDoctrine()->getRepository(Wallet::class)->findOneBy(['user' => $this->getUser()]),
        ]);
    }
}
