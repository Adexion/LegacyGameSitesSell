<?php

namespace ModernGame\Controller\AfterLogin;

use ModernGame\Database\Entity\ItemList;
use ModernGame\Database\Entity\Price;
use ModernGame\Service\Connection\Minecraft\RCONService;
use ModernGame\Service\Connection\Payment\PayPal\PayPalService;
use ModernGame\Service\Content\ItemListService;
use ModernGame\Service\User\WalletService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ItemShopController extends Controller
{
    public function getItemList()
    {
        return new JsonResponse(
            $this->getDoctrine()->getRepository(ItemList::class)->findAll()
        );
    }

    public function payPalExecute(Request $request, PayPalService $payPal, RCONService $rcon)
    {
        $paymentId = $request->request->get('paymentId') ?? 0;
        $payerId = $request->request->get('payerId') ?? 0;

        $amount = $payPal->executePayment($paymentId, $payerId, false);

        return new JsonResponse(
            $rcon->executeItemListForDonation(
                $amount,
                $request->request->getInt('itemListId') ?? 0,
                $request->request->get('username') ?? 'Steve'
            )
        );
    }

    public function buyItemList(Request $request, WalletService $wallet, ItemListService $itemListService)
    {
        $cash = $wallet->changeCash(
            -$itemListService->getItemListPrice($request->request->getInt('id'))
        );

        $itemListService->assignListToUser($request->request->getInt('id'));

        return new JsonResponse([
            "cash" => $cash
        ]);
    }

    public function getSMSPrices()
    {
        return new JsonResponse(
            $this->getDoctrine()->getRepository(Price::class)->findAll()
        );
    }

    public function itemExecute(Request $request, RCONService $rcon)
    {
        return new JsonResponse($rcon->executeItem($request->request->getInt('itemId')));
    }

    public function itemListExecute(RCONService $rcon)
    {
        return new JsonResponse($rcon->executeItem());
    }
}
