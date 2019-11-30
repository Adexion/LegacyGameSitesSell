<?php

namespace ModernGame\Controller\Admin;

use ModernGame\Database\Entity\ModList;
use ModernGame\Service\Content\ModListService;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ModController extends AbstractAdminController
{
    protected const REPOSITORY_CLASS = ModList::class;

    /**
     * @SWG\Tag(name="Admin/Mod")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function postMod(Request $request, ModListService $modListService): JsonResponse
    {
        return $this->postEntity($request, $modListService);
    }

    /**
     * @SWG\Tag(name="Admin/Mod")
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
     * @SWG\Tag(name="Admin/Mod")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function putMod(Request $request, ModListService $modListService): JsonResponse
    {
        return $this->putEntity($request, $modListService);
    }

    /**
     * @SWG\Tag(name="Admin/Mod")
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
