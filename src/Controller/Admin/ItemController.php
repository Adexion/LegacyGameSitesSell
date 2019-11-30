<?php

namespace ModernGame\Controller\Admin;

use ModernGame\Database\Entity\Item;
use ModernGame\Service\Content\ItemService;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ItemController extends AbstractAdminController
{
    protected const REPOSITORY_CLASS = Item::class;

    /**
     * @SWG\Tag(name="Admin/Item")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function postItem(Request $request, ItemService $itemService): JsonResponse
    {
        return $this->postEntity($request, $itemService);
    }

    /**
     * @SWG\Tag(name="Admin/Item")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function getEntity(Request $request): JsonResponse
    {
        return parent::getEntity($request);
    }

    /**
     * @SWG\Tag(name="Admin/Item")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function putItem(Request $request, ItemService $itemService): JsonResponse
    {
        return $this->putEntity($request, $itemService);
    }

    /**
     * @SWG\Tag(name="Admin/Item")
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
