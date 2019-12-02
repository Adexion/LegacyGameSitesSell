<?php

namespace ModernGame\Controller\AfterLogin;

use ModernGame\Database\Entity\ItemList;
use ModernGame\Database\Entity\Price;
use ModernGame\Service\Connection\Minecraft\RCONService;
use ModernGame\Service\Connection\Payment\DotPay\DotPayService;
use ModernGame\Service\Connection\Payment\PayPal\PayPalService;
use ModernGame\Service\Content\ItemListService;
use ModernGame\Service\User\WalletService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Swagger\Annotations as SWG;

class ItemShopController extends Controller
{
    /**
     * @SWG\Tag(name="Shop")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function getItemList()
    {
        return new JsonResponse(
            $this->getDoctrine()->getRepository(ItemList::class)->findAll()
        );
    }

    /**
     * @SWG\Tag(name="Shop")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function payPalExecute(Request $request, PayPalService $payment, RCONService $rcon)
    {
        $paymentId = $request->request->get('paymentId') ?? 0;
        $payerId = $request->request->get('payerId') ?? 0;

        $amount = $payment->executePayment($paymentId, $payerId, false);

        return new JsonResponse(
            $rcon->executeItemListForDonation(
                $amount,
                $request->request->getInt('itemListId') ?? 0,
                $request->request->get('username') ?? 'Steve'
            )
        );
    }

    /**
     * @SWG\Tag(name="Shop")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function dotPayExecute(Request $request, DotPayService $payment, RCONService $rcon)
    {
        $paymentId = $request->request->get('paymentId') ?? 0;

        $amount = $payment->executePayment($paymentId, null, false);

        return new JsonResponse(
            $rcon->executeItemListForDonation(
                $amount,
                $request->request->getInt('itemListId') ?? 0,
                $request->request->get('username') ?? 'Steve'
            )
        );
    }

    /**
     * @SWG\Tag(name="Shop")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
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

    /**
     * @SWG\Tag(name="Shop")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function getSMSPrices()
    {
        return new JsonResponse(
            $this->getDoctrine()->getRepository(Price::class)->findAll()
        );
    }
}
