<?php

namespace ModernGame\Controller\Admin;

use ModernGame\Database\Entity\ItemList;
use ModernGame\Database\Entity\ModList;
use ModernGame\Database\Repository\ModListRepository;
use ModernGame\Service\Content\ModListService;
use ModernGame\Service\Serializer;
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
        $modList = $modListService->mapEntityById($request);

        /** @var ModListRepository $modListRepository */
        $modListRepository = $this->getDoctrine()->getRepository(ModList::class);
        $modListRepository->update($modList);

        return new JsonResponse(null, Response::HTTP_OK);
    }

    public function postMod(Request $request, ModListService $modListService)
    {
        $modList = $modListService->mapEntity($request);

        /** @var ModListRepository $modListRepository */
        $modListRepository = $this->getDoctrine()->getRepository(ModList::class);
        $modListRepository->insert($modList);

        return new JsonResponse(null, Response::HTTP_OK);
    }

    public function getMod(Request $request, Serializer $serializer)
    {
        $repository = $this->getDoctrine()->getRepository(ModList::class);
        $id = $request->query->getInt('id');

        return new JsonResponse(
            $serializer->toArray(empty($id) ? $repository->findAll() : $repository->find($id))
        );
    }
}
