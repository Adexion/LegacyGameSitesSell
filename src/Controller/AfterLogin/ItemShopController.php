<?php

namespace ModernGame\Controller\AfterLogin;

use ModernGame\Database\Entity\ItemList;
use ModernGame\Database\Entity\Price;
use ModernGame\Service\Connection\Minecraft\RCONService;
use ModernGame\Service\Connection\Payment\DotPay\DotPayService;
use ModernGame\Service\Connection\Payment\PayPal\PayPalService;
use ModernGame\Service\Content\ItemListService;
use ModernGame\Service\User\WalletService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Swagger\Annotations as SWG;

class ItemShopController extends Controller
{
    /**
     * Return list of items lists
     *
     * It returns items list which can be buy be users
     *
     * @SWG\Tag(name="Shop")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     *     @SWG\Schema(
     *          type="array",
     *          @SWG\Items(ref=@Model(type=ItemList::class)),
     *     )
     * )
     */
    public function getItemList()
    {
        return new JsonResponse(
            $this->getDoctrine()->getRepository(ItemList::class)->findAll()
        );
    }

    /**
     * Execute instantly items without using prepaid account or donate by PayPal
     *
     * Can buy items and instantly execute it on minecraft server or without giving username only give donation for server.
     *
     * @SWG\Tag(name="Shop")
     * @SWG\Parameter(
     *     type="object",
     *     in="body",
     *     name="JSON",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(
     *              type="string",
     *              property="paymentId"
     *          ),
     *          @SWG\Property(
     *              type="string",
     *              property="payerId"
     *          )
     *     )
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     *     @SWG\Schema(
     *          type="array",
     *          @SWG\Items(type="string", minItems=1),
     *     )
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
     * Execute instantly items without using prepaid account or donate by DotPay
     *
     * Can buy items and instantly execute it on minecraft server or without giving username only give donation for server.
     *
     * @SWG\Tag(name="Shop")
     * @SWG\Parameter(
     *     type="object",
     *     in="body",
     *     name="JSON",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(
     *              type="string",
     *              property="paymentId"
     *          )
     *     )
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     *     @SWG\Schema(
     *          type="array",
     *          @SWG\Items(type="string", minItems=1),
     *     )
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
     * Execute items using prepaid status
     *
     * Returns prepaid status after decreasing amount for completing the item
     *
     * @SWG\Tag(name="Shop")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(
     *              type="integer",
     *              property="cah"
     *          )
     *     )
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
     * Return prices of SMS code
     *
     * Get information about provisions for SMS codes
     *
     * @SWG\Tag(name="Shop")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     *     @SWG\Schema(
     *          type="array",
     *          @SWG\Items(ref=@Model(type=Price::class))
     *     )
     * )
     */
    public function getSMSPrices()
    {
        return new JsonResponse(
            $this->getDoctrine()->getRepository(Price::class)->findAll()
        );
    }
}
