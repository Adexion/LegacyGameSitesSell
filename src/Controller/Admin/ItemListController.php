<?php

namespace ModernGame\Controller\Admin;

use ModernGame\Database\Entity\ItemList;
use ModernGame\Serializer\CustomSerializer;
use ModernGame\Service\Content\ItemListService;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ItemListController extends AbstractAdminController
{
    protected const REPOSITORY_CLASS = ItemList::class;

    /**
     * @SWG\Tag(name="Admin/ItemList")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function postItemList(Request $request, ItemListService $itemListService): JsonResponse
    {
        return $this->postEntity($request, $itemListService);
    }

    /**
     * @SWG\Tag(name="Admin/ItemList")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function getEntity(Request $request, CustomSerializer $serializer): JsonResponse
    {
        return parent::getEntity($request, $serializer);
    }

    /**
     * @SWG\Tag(name="Admin/ItemList")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function putItemList(Request $request, ItemListService $itemListService): JsonResponse
    {
        return $this->putEntity($request, $itemListService);
    }

    /**
     * @SWG\Tag(name="Admin/ItemList")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function deleteEntity(Request $request): JsonResponse
    {
        return parent::deleteEntity($request);
    }
}
