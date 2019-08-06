<?php

namespace ModernGame\Controller;

use ModernGame\Service\Connection\RCONService;
use ModernGame\Service\Content\EquipmentService;
use ModernGame\Service\User\WalletService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ItemShopController extends Controller
{
    public function buyEquipment(Request $request, WalletService $wallet, EquipmentService $equipment)
    {
        $cash = $wallet->changeCash(
            $this->getUser()->getId(),
            -$equipment->getEquipmentPrice($request->request->getInt('equipmentId'))
        );

        $equipment->assignEquipmentToUser($request->request->getInt('equipmentId'), $this->getUser()->getId());

        return new JsonResponse([
            "cash" => $cash
        ]);
    }

    public function itemExecute(Request $request, RCONService $rcon)
    {
        return new JsonResponse($rcon->executeItem(
            $request->request->get('itemId')
        ));
    }

    public function itemListExecute(RCONService $rcon)
    {
        return new JsonResponse($rcon->executeItem());
    }
}
