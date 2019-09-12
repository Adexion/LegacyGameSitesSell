<?php

namespace ModernGame\Controller\Admin;

use ModernGame\Database\Entity\Item;
use ModernGame\Database\Repository\ItemRepository;
use ModernGame\Service\Content\ItemService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ItemController extends AbstractController
{
    public function deleteItem(Request $request)
    {
        /** @var ItemRepository $itemRepository */
        $itemRepository = $this->getDoctrine()->getRepository(Item::class);

        $itemRepository->delete($request->request->getInt('id'));

        return new JsonResponse(null, Response::HTTP_OK);
    }

    public function putItem(Request $request, ItemService $itemService)
    {
        $item = $itemService->mapEntityById($request);

        /** @var ItemRepository $itemRepository */
        $itemRepository = $this->getDoctrine()->getRepository(Item::class);
        $itemRepository->update($item);

        return new JsonResponse(null, Response::HTTP_OK);
    }

    public function postItem(Request $request, ItemService $itemService)
    {
        $item = $itemService->mapEntity($request);

        /** @var ItemRepository $itemRepository */
        $itemRepository = $this->getDoctrine()->getRepository(Item::class);
        $itemRepository->insert($item);

        return new JsonResponse(null, Response::HTTP_OK);
    }

    public function getItem(Request $request)
    {
        return new JsonResponse(
            $this->getDoctrine()->getRepository(Item::class)->find($request->query->getInt('id'))
        );
    }

    public function getItems()
    {
        return new JsonResponse(
            $this->getDoctrine()->getRepository(Item::class)->findAll()
        );
    }
}
