<?php

namespace ModernGame\Controller\Admin;

use ModernGame\Database\Entity\Equipment;
use ModernGame\Database\Repository\EquipmentRepository;
use ModernGame\Service\Content\EquipmentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EquipmentController extends AbstractController
{
    public function deleteEquipment(Request $request)
    {
        /** @var EquipmentRepository $equipmentRepository */
        $equipmentRepository = $this->getDoctrine()->getRepository(Equipment::class);

        $equipmentRepository->delete($request->request->getInt('id'));

        return new JsonResponse(null, Response::HTTP_OK);
    }

    public function putEquipment(Request $request, EquipmentService $equipmentService)
    {
        $equipment = $equipmentService->getMappedEquipment($request);

        /** @var EquipmentRepository $equipmentRepository */
        $equipmentRepository = $this->getDoctrine()->getRepository(Equipment::class);
        $equipmentRepository->update($equipment);

        return new JsonResponse(null, Response::HTTP_OK);
    }

    public function postEquipment(Request $request, EquipmentService $equipmentService)
    {
        $equipment = $equipmentService->getMappedEquipment($request);

        /** @var EquipmentRepository $equipmentRepository */
        $equipmentRepository = $this->getDoctrine()->getRepository(Equipment::class);
        $equipmentRepository->insert($equipment);

        return new JsonResponse(null, Response::HTTP_OK);
    }
}
