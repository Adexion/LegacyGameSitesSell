<?php

namespace ModernGame\Controller\Admin;

use ModernGame\Database\Entity\ItemList;
use ModernGame\Database\Repository\ItemListRepository;
use ModernGame\Service\Content\ItemListService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;

class ItemListController extends AbstractController
{
    /**
     * @SWG\Tag(name="Admin/ItemList")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function deleteItemList(Request $request)
    {
        /** @var ItemListRepository $listRepository */
        $listRepository = $this->getDoctrine()->getRepository(ItemList::class);

        $listRepository->delete($request->request->getInt('id'));

        return new JsonResponse(null, Response::HTTP_OK);
    }

    /**
     * @SWG\Tag(name="Admin/ItemList")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function putItemList(Request $request, ItemListService $itemListService)
    {
        $itemList = $itemListService->mapEntityById($request);

        /** @var ItemListRepository $itemListRepository */
        $itemListRepository = $this->getDoctrine()->getRepository(ItemList::class);
        $itemListRepository->update($itemList);

        return new JsonResponse(null, Response::HTTP_OK);
    }

    /**
     * @SWG\Tag(name="Admin/ItemList")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function postItemList(Request $request, ItemListService $itemListService)
    {
        $mapItemList = $itemListService->mapEntity($request);

        /** @var ItemListRepository $itemListRepository */
        $itemListRepository = $this->getDoctrine()->getRepository(ItemList::class);
        $itemListRepository->insert($mapItemList);

        return new JsonResponse(null, Response::HTTP_OK);
    }

    /**
     * @SWG\Tag(name="Admin/ItemList")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function getItemList(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(ItemList::class);
        $id = $request->query->getInt('id');

        return new JsonResponse(empty($id) ? $repository->findAll() : $repository->find($id));
    }
}
