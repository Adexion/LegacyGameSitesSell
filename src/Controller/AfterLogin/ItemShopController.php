<?php

namespace ModernGame\Controller\AfterLogin;

use ModernGame\Database\Entity\ItemList;
use ModernGame\Service\Connection\Minecraft\RCONService;
use ModernGame\Service\Content\ItemListService;
use ModernGame\Service\User\WalletService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ItemShopController extends Controller
{
    public function getItemList()
    {
        return new JsonResponse($this->getDoctrine()->getRepository(ItemList::class)->findAll());
    }

    public function buyItemList(Request $request, WalletService $wallet, ItemListService $itemListService)
    {
        $cash = $wallet->changeCash(
            $this->getUser()->getId(),
            -$itemListService->getItemListPrice($request->request->getInt('id'))
        );

        $itemListService->assignListToUser($request->request->getInt('id'), $this->getUser()->getId());

        return new JsonResponse([
            "cash" => $cash
        ]);
    }

    //ToDo: add test after add a rcon service
    public function itemExecute(Request $request, RCONService $rcon)
    {
        return new JsonResponse($rcon->executeItem($request->request->get('itemId')));
    }

    //ToDo: add test after add a rcon service
    public function itemListExecute(RCONService $rcon)
    {
        return new JsonResponse($rcon->executeItem());
    }
}
