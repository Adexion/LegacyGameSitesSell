<?php

namespace ModernGame\Controller\Admin;

use ModernGame\Database\Entity\RegulationCategory;
use ModernGame\Database\Repository\RegulationCategoryRepository;
use ModernGame\Service\Content\RegulationCategoryService;
use ModernGame\Serializer\CustomSerializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegulationCategoryController extends AbstractController
{
    public function postRegulationCategory(Request $request, RegulationCategoryService $regulationService)
    {
        $regulation = $regulationService->mapEntity($request);

        /** @var RegulationCategoryRepository $regulationCategoryRepository */
        $regulationCategoryRepository = $this->getDoctrine()->getRepository(RegulationCategory::class);
        $regulationCategoryRepository->insert($regulation);

        return new JsonResponse(null, Response::HTTP_OK);
    }

    public function putRegulationCategory(Request $request, RegulationCategoryService $regulationService)
    {
        $regulation = $regulationService->mapEntityById($request);

        /** @var RegulationCategoryRepository $regulationCategoryRepository */
        $regulationCategoryRepository = $this->getDoctrine()->getRepository(RegulationCategory::class);
        $regulationCategoryRepository->update($regulation);

        return new JsonResponse(null, Response::HTTP_OK);
    }

    public function deleteRegulationCategory(Request $request)
    {
        /** @var RegulationCategoryRepository $regulationCategoryRepository */
        $regulationCategoryRepository = $this->getDoctrine()->getRepository(RegulationCategory::class);
        $regulationCategoryRepository->delete($request->request->getInt('id'));

        return new JsonResponse(null, Response::HTTP_OK);
    }

    public function getRegulationCategory(Request $request, CustomSerializer $serializer)
    {
        $repository = $this->getDoctrine()->getRepository(RegulationCategory::class);
        $id = $request->query->getInt('id');

        return new JsonResponse(
            $serializer->toArray(empty($id) ? $repository->findAll() : $repository->find($id))
        );
    }
}
