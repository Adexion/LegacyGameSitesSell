<?php

namespace ModernGame\Controller\AfterLogin;

use ModernGame\Database\Entity\ItemList;
use ModernGame\Database\Entity\ItemListStatistic;
use ModernGame\Database\Entity\Price;
use ModernGame\Service\Connection\Minecraft\RCONService;
use ModernGame\Service\Content\ItemListService;
use ModernGame\Serializer\CustomSerializer;
use ModernGame\Service\User\WalletService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ItemShopController extends Controller
{
    public function getItemList(CustomSerializer $serializer)
    {
        return new JsonResponse(
            $this->getDoctrine()->getRepository(ItemList::class)->findAll()
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
        return new JsonResponse($rcon->executeItem($request->request->get('itemId')));
    }

    public function itemListExecute(RCONService $rcon)
    {
        return new JsonResponse($rcon->executeItem());
    }
}
