<?php

namespace ModernGame\Controller\Backend;

use ModernGame\Database\Entity\ItemList;
use ModernGame\Database\Entity\Price;
use ModernGame\Serializer\CustomSerializer;
use ModernGame\Service\Connection\Minecraft\RCONService;
use ModernGame\Service\Connection\Payment\MicroSMS\MicroSMSService;
use ModernGame\Service\Connection\Payment\PayPal\PayPalService;
use ModernGame\Service\Content\ItemListService;
use ModernGame\Service\User\WalletService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserProviderInterface;

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
     *              property="orderId"
     *          ),
     *          @SWG\Property(
     *              type="string",
     *              property="itemListId"
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
    public function payPalExecute(Request $request, PayPalService $payment, RCONService $rcon, UserProviderInterface $userProvider): JsonResponse
    {
        $amount = $payment->executePayment($request->request->get('orderId') ?? 0, $request->request->get('username'));

        return new JsonResponse(
            $rcon->executeItemListInstant(
                $amount,
                $request->request->getInt('itemListId') ?? 0,
                $userProvider->loadUserByUsername($request->request->get('username'))
            )
        );
    }

    /**
     * Execute instantly items without using prepaid account or donate by MicroSMS
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
     *              property="smsCode"
     *          ),
     *          @SWG\Property(
     *              type="string",
     *              property="itemListId"
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
    public function microSMSExecute(Request $request, MicroSMSService $payment, RCONService $rcon, UserProviderInterface $userProvider): JsonResponse
    {
        $amount = $payment->executePayment($request->request->get('smsCode') ?? 0, $request->request->get('username'));

        return new JsonResponse(
            $rcon->executeItemListInstant(
                $amount,
                $request->request->getInt('itemListId') ?? 0,
                $userProvider->loadUserByUsername($request->request->get('username'))
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
