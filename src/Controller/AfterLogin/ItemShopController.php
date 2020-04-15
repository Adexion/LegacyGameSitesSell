<?php

namespace ModernGame\Controller\AfterLogin;

use ModernGame\Database\Entity\ItemList;
use ModernGame\Database\Entity\Price;
use ModernGame\Serializer\CustomSerializer;
use ModernGame\Service\Connection\Minecraft\RCONService;
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
    public function getItemList(CustomSerializer $serializer): JsonResponse
    {
        return new JsonResponse($serializer->serialize(
            $this->getDoctrine()->getRepository(ItemList::class)->findAll()
        )->toArray());
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
    public function payPalExecute(Request $request, PayPalService $payment, RCONService $rcon): JsonResponse
    {
        $amount = $payment->executePayment($request->request->get('orderId') ?? 0, true);

        return new JsonResponse(
            $rcon->executeItemListForDonation(
                $amount,
                $request->request->getInt('itemListId') ?? 0,
                $this->getUser()->getUsername()
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
    public function buyItemList(Request $request, WalletService $wallet, ItemListService $itemListService): JsonResponse
    {
        $cash = $wallet->changeCash(
            -$itemListService->getItemListPrice($request->request->getInt('id')),
            $this->getUser()
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
    public function getSMSPrices(CustomSerializer $serializer): JsonResponse
    {
        return new JsonResponse($serializer->serialize(
            $this->getDoctrine()->getRepository(Price::class)->findAll()
        )->toArray());
    }
}
