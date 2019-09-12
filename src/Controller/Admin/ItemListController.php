<?php

namespace ModernGame\Controller\Admin;

use ModernGame\Database\Entity\ItemList;
use ModernGame\Database\Repository\ItemListRepository;
use ModernGame\Service\Content\ItemListService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ItemListController extends AbstractController
{
    public function deleteItemList(Request $request)
    {
        /** @var ItemListRepository $listRepository */
        $listRepository = $this->getDoctrine()->getRepository(ItemList::class);

        $listRepository->delete($request->request->getInt('id'));

        return new JsonResponse(null, Response::HTTP_OK);
    }

    public function putItemList(Request $request, ItemListService $itemListService)
    {
        $itemList = $itemListService->mapEntityById($request);

        /** @var ItemListRepository $itemListRepository */
        $itemListRepository = $this->getDoctrine()->getRepository(ItemList::class);
        $itemListRepository->update($itemList);

        return new JsonResponse(null, Response::HTTP_OK);
    }

    public function postItemList(Request $request, ItemListService $itemListService)
    {
        $mapItemList = $itemListService->mapEntity($request);

        /** @var ItemListRepository $itemListRepository */
        $itemListRepository = $this->getDoctrine()->getRepository(ItemList::class);
        $itemListRepository->insert($mapItemList);

        return new JsonResponse(null, Response::HTTP_OK);
    }

    public function getItemList(Request $request)
    {
        return new JsonResponse(
            $this->getDoctrine()->getRepository(ItemList::class)->find($request->query->getInt('id'))
        );
    }

    public function getItemLists()
    {
        return new JsonResponse(
            $this->getDoctrine()->getRepository(ItemList::class)->findAll()
        );
    }
}
