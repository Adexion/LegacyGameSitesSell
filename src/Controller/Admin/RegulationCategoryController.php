<?php

namespace ModernGame\Controller\Admin;

use ModernGame\Database\Entity\RegulationCategory;
use ModernGame\Database\Repository\RegulationCategoryRepository;
use ModernGame\Service\Content\RegulationCategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;

class RegulationCategoryController extends AbstractController
{
    /**
     * @SWG\Tag(name="Admin/RegulationCategory")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function postRegulationCategory(Request $request, RegulationCategoryService $regulationService)
    {
        $regulation = $regulationService->mapEntity($request);

        /** @var RegulationCategoryRepository $regulationCategoryRepository */
        $regulationCategoryRepository = $this->getDoctrine()->getRepository(RegulationCategory::class);
        $regulationCategoryRepository->insert($regulation);

        return new JsonResponse(null, Response::HTTP_OK);
    }

    /**
     * @SWG\Tag(name="Admin/RegulationCategory")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function putRegulationCategory(Request $request, RegulationCategoryService $regulationService)
    {
        $regulation = $regulationService->mapEntityById($request);

        /** @var RegulationCategoryRepository $regulationCategoryRepository */
        $regulationCategoryRepository = $this->getDoctrine()->getRepository(RegulationCategory::class);
        $regulationCategoryRepository->update($regulation);

        return new JsonResponse(null, Response::HTTP_OK);
    }

    /**
     * @SWG\Tag(name="Admin/RegulationCategory")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function deleteRegulationCategory(Request $request)
    {
        /** @var RegulationCategoryRepository $regulationCategoryRepository */
        $regulationCategoryRepository = $this->getDoctrine()->getRepository(RegulationCategory::class);
        $regulationCategoryRepository->delete($request->request->getInt('id'));

        return new JsonResponse(null, Response::HTTP_OK);
    }

    /**
     * @SWG\Tag(name="Admin/RegulationCategory")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function getRegulationCategory(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(RegulationCategory::class);
        $id = $request->query->getInt('id');

        return new JsonResponse(empty($id) ? $repository->findAll() : $repository->find($id));
    }
}
