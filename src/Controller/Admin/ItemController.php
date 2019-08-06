<?php

namespace ModernGame\Controller\Admin;

use ModernGame\Database\Entity\EquipmentItem;
use ModernGame\Database\Repository\EquipmentItemRepository;
use ModernGame\Service\Content\EquipmentItemService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ItemController extends AbstractController
{
    public function deleteItem(Request $request)
    {
        /** @var EquipmentItemRepository $equipmentItemRepository */
        $equipmentItemRepository = $this->getDoctrine()->getRepository(EquipmentItem::class);

        $equipmentItemRepository->delete($request->request->getInt('id'));

        return new JsonResponse(null, Response::HTTP_OK);
    }

    public function putItem(Request $request, EquipmentItemService $equipmentItemService)
    {
        $equipmentItem = $equipmentItemService->getMappedEquipmentItem($request);

        /** @var EquipmentItemRepository $equipmentItemRepository */
        $equipmentItemRepository = $this->getDoctrine()->getRepository(EquipmentItem::class);
        $equipmentItemRepository->update($equipmentItem);

        return new JsonResponse(null, Response::HTTP_OK);
    }

    public function postItem(Request $request, EquipmentItemService $equipmentItemService)
    {
        $equipmentItem = $equipmentItemService->getMappedEquipmentItem($request);

        /** @var EquipmentItemRepository $equipmentItemRepository */
        $equipmentItemRepository = $this->getDoctrine()->getRepository(EquipmentItem::class);
        $equipmentItemRepository->insert($equipmentItem);

        return new JsonResponse(null, Response::HTTP_OK);
    }
}
