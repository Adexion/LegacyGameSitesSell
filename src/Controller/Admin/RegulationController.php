<?php

namespace ModernGame\Controller\Admin;

use ModernGame\Database\Entity\Regulation;
use ModernGame\Database\Repository\RegulationRepository;
use ModernGame\Service\Content\Regulation\RegulationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;

class RegulationController extends AbstractController
{
    /**
     * @SWG\Tag(name="Admin/Regulation")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function postRegulation(Request $request, RegulationService $regulationService)
    {
        $regulation = $regulationService->mapEntity($request);

        /** @var RegulationRepository $regulationCategoryRepository */
        $regulationCategoryRepository = $this->getDoctrine()->getRepository(Regulation::class);
        $regulationCategoryRepository->insert($regulation);

        return new JsonResponse(null, Response::HTTP_OK);
    }

    /**
     * @SWG\Tag(name="Admin/Regulation")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function putRegulation(Request $request, RegulationService $regulationService)
    {
        $regulation = $regulationService->mapEntityById($request);

        /** @var RegulationRepository $regulationCategoryRepository */
        $regulationCategoryRepository = $this->getDoctrine()->getRepository(Regulation::class);
        $regulationCategoryRepository->update($regulation);

        return new JsonResponse(null, Response::HTTP_OK);
    }

    /**
     * @SWG\Tag(name="Admin/Regulation")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function deleteRegulation(Request $request)
    {
        /** @var RegulationRepository $regulationCategoryRepository */
        $regulationCategoryRepository = $this->getDoctrine()->getRepository(Regulation::class);
        $regulationCategoryRepository->delete($request->request->getInt('id'));

        return new JsonResponse(null, Response::HTTP_OK);
    }

    /**
     * @SWG\Tag(name="Admin/Regulation")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function getRegulation(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Regulation::class);
        $id = $request->query->getInt('id');

        return new JsonResponse(empty($id) ? $repository->findAll() : $repository->find($id));
    }
}
