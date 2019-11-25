<?php

namespace ModernGame\Controller\Admin;

use ModernGame\Database\Entity\ModList;
use ModernGame\Database\Repository\ModListRepository;
use ModernGame\Service\Content\ModListService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;

class ModController extends AbstractController
{
    /**
     * @SWG\Tag(name="Admin/Mod")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function deleteMod(Request $request)
    {
        /** @var ModListRepository $modListRepository */
        $modListRepository = $this->getDoctrine()->getRepository(ModList::class);
        $modListRepository->delete($request->request->getInt('id'));

        return new JsonResponse(null, Response::HTTP_OK);
    }

    /**
     * @SWG\Tag(name="Admin/Mod")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function putMod(Request $request, ModListService $modListService)
    {
        $modList = $modListService->mapEntityById($request);

        /** @var ModListRepository $modListRepository */
        $modListRepository = $this->getDoctrine()->getRepository(ModList::class);
        $modListRepository->update($modList);

        return new JsonResponse(null, Response::HTTP_OK);
    }

    /**
     * @SWG\Tag(name="Admin/Mod")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function postMod(Request $request, ModListService $modListService)
    {
        $modList = $modListService->mapEntity($request);

        /** @var ModListRepository $modListRepository */
        $modListRepository = $this->getDoctrine()->getRepository(ModList::class);
        $modListRepository->insert($modList);

        return new JsonResponse(null, Response::HTTP_OK);
    }

    /**
     * @SWG\Tag(name="Admin/Mod")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function getMod(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(ModList::class);
        $id = $request->query->getInt('id');

        return new JsonResponse(empty($id) ? $repository->findAll() : $repository->find($id));
    }
}
