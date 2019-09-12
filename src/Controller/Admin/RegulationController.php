<?php

namespace ModernGame\Controller\Admin;

use ModernGame\Database\Entity\Regulation;
use ModernGame\Database\Repository\RegulationRepository;
use ModernGame\Service\Content\RegulationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegulationController extends AbstractController
{
    public function postRegulation(Request $request, RegulationService $regulationService)
    {
        $regulation = $regulationService->mapEntity($request);

        /** @var RegulationRepository $regulationCategoryRepository */
        $regulationCategoryRepository = $this->getDoctrine()->getRepository(Regulation::class);
        $regulationCategoryRepository->insert($regulation);

        return new JsonResponse(null, Response::HTTP_OK);
    }

    public function putRegulation(Request $request, RegulationService $regulationService)
    {
        $regulation = $regulationService->mapEntityById($request);

        /** @var RegulationRepository $regulationCategoryRepository */
        $regulationCategoryRepository = $this->getDoctrine()->getRepository(Regulation::class);
        $regulationCategoryRepository->update($regulation);

        return new JsonResponse(null, Response::HTTP_OK);
    }

    public function deleteRegulation(Request $request)
    {
        /** @var RegulationRepository $regulationCategoryRepository */
        $regulationCategoryRepository = $this->getDoctrine()->getRepository(Regulation::class);
        $regulationCategoryRepository->delete($request->request->getInt('id'));

        return new JsonResponse(null, Response::HTTP_OK);
    }

    public function getRegulation(Request $request)
    {
        return new JsonResponse(
            $this->getDoctrine()->getRepository(Regulation::class)->find($request->query->getInt('id'))
        );
    }

    public function getRegulations()
    {
        return new JsonResponse(
            $this->getDoctrine()->getRepository(Regulation::class)->findAll()
        );
    }
}
