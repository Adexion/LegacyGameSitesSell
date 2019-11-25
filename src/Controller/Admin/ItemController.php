<?php

namespace ModernGame\Controller\Admin;

use ModernGame\Database\Entity\Item;
use ModernGame\Database\Repository\ItemRepository;
use ModernGame\Service\Content\ItemService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;

class ItemController extends AbstractController
{
    /**
     * @SWG\Tag(name="Item")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function deleteItem(Request $request)
    {
        /** @var ItemRepository $itemRepository */
        $itemRepository = $this->getDoctrine()->getRepository(Item::class);

        $itemRepository->delete($request->request->getInt('id'));

        return new JsonResponse(null, Response::HTTP_OK);
    }

    /**
     * @SWG\Tag(name="Item")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function putItem(Request $request, ItemService $itemService)
    {
        $item = $itemService->mapEntityById($request);

        /** @var ItemRepository $itemRepository */
        $itemRepository = $this->getDoctrine()->getRepository(Item::class);
        $itemRepository->update($item);

        return new JsonResponse(null, Response::HTTP_OK);
    }

    /**
     * @SWG\Tag(name="Item")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function postItem(Request $request, ItemService $itemService)
    {
        $item = $itemService->mapEntity($request);

        /** @var ItemRepository $itemRepository */
        $itemRepository = $this->getDoctrine()->getRepository(Item::class);
        $itemRepository->insert($item);

        return new JsonResponse(null, Response::HTTP_OK);
    }

    /**
     * @SWG\Tag(name="Item")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function getItem(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Item::class);
        $id = $request->query->getInt('id');

        return new JsonResponse(empty($id) ? $repository->findAll() : $repository->find($id));
    }
}
