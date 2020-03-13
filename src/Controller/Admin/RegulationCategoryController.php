<?php

namespace ModernGame\Controller\Admin;

use ModernGame\Database\Entity\RegulationCategory;
use ModernGame\Serializer\CustomSerializer;
use ModernGame\Service\Content\RegulationCategoryService;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RegulationCategoryController extends AbstractAdminController
{
    protected const REPOSITORY_CLASS = RegulationCategory::class;

    /**
     * @SWG\Tag(name="Admin/RegulationCategory")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function postRegulationCategory(Request $request, RegulationCategoryService $regulationService): JsonResponse
    {
        return $this->postEntity($request, $regulationService);
    }

    /**
     * @SWG\Tag(name="Admin/RegulationCategory")
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
     * @SWG\Tag(name="Admin/RegulationCategory")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function putRegulationCategory(Request $request, RegulationCategoryService $regulationService): JsonResponse
    {
        return $this->putEntity($request, $regulationService);
    }

    /**
     * @SWG\Tag(name="Admin/RegulationCategory")
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
