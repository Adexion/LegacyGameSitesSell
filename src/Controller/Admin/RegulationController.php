<?php

namespace ModernGame\Controller\Admin;

use ModernGame\Database\Entity\Regulation;
use ModernGame\Service\Content\Regulation\RegulationService;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RegulationController extends AbstractAdminController
{
    protected const REPOSITORY_CLASS = Regulation::class;

    /**
     * @SWG\Tag(name="Admin/Regulation")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function postRegulation(Request $request, RegulationService $regulationService): JsonResponse
    {
        return $this->postEntity($request, $regulationService);
    }

    /**
     * @SWG\Tag(name="Admin/Regulation")
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
     * @SWG\Tag(name="Admin/Regulation")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function putRegulation(Request $request, RegulationService $regulationService): JsonResponse
    {
        return $this->putEntity($request, $regulationService);
    }

    /**
     * @SWG\Tag(name="Admin/Regulation")
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
