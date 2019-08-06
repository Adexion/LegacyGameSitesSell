<?php

namespace ModernGame\Controller\Admin;

use ModernGame\Database\Entity\ModList;
use ModernGame\Database\Repository\ModListRepository;
use ModernGame\Service\Content\ModListService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ModController extends AbstractController
{
    public function deleteMod(Request $request)
    {
        /** @var ModListRepository $modListRepository */
        $modListRepository = $this->getDoctrine()->getRepository(ModList::class);
        $modListRepository->delete($request->request->getInt('id'));

        return new JsonResponse(null, Response::HTTP_OK);
    }

    public function putMod(Request $request, ModListService $modListService)
    {
        $modList = $modListService->getMappedMod($request);

        /** @var ModListRepository $modListRepository */
        $modListRepository = $this->getDoctrine()->getRepository(ModList::class);
        $modListRepository->update($modList);

        return new JsonResponse(null, Response::HTTP_OK);
    }

    public function postMod(Request $request, ModListService $modListService)
    {
        $modList = $modListService->getMappedMod($request);

        /** @var ModListRepository $modListRepository */
        $modListRepository = $this->getDoctrine()->getRepository(ModList::class);
        $modListRepository->insert($modList);

        return new JsonResponse(null, Response::HTTP_OK);
    }
}
