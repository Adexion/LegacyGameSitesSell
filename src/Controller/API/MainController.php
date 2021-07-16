<?php

namespace MNGame\Controller\API;

use MNGame\Database\Entity\AdminServerUser;
use MNGame\Database\Entity\Article;
use MNGame\Database\Entity\ItemList;
use MNGame\Database\Repository\RegulationRepository;
use MNGame\Service\ServerProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route(name="api-index", path="/api/main", methods={"GET"})
     */
    public function getIndex(ServerProvider $serverProvider, Request $request, Session $session): JsonResponse
    {
        $session->set('serverId', $request->request->get('serverId', $session->get('serverId', 1)));

        return new JsonResponse([
            'articleList' => $this->getDoctrine()->getRepository(Article::class)->getLastArticles(),
            'admins'      => $this->getDoctrine()->getRepository(AdminServerUser::class)->findBy(['serverId' => $serverProvider->getSessionServer()->getId()]),
            'itemList'    => $this->getDoctrine()->getRepository(ItemList::class)->findBy(['serverId' => $serverProvider->getSessionServer()->getId()]),
        ]);
    }

    /**
     * @Route(name="api-rule", path="/api/rule", methods={"GET"})
     */
    public function getRule(RegulationRepository $repository): Response
    {
        return new JsonResponse([
            'ruleList' => $repository->getRegulationList(),
        ]);
    }
}