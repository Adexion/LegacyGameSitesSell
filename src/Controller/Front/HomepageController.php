<?php

namespace MNGame\Controller\Front;

use MNGame\Database\Entity\AdminServerUser;
use MNGame\Database\Entity\Article;
use MNGame\Database\Entity\ItemList;
use MNGame\Database\Repository\RegulationRepository;
use MNGame\Service\Connection\Minecraft\ExecutionService;
use MNGame\Service\ServerProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /**
     * @Route(name="index", path="/")
     */
    public function index(
        ExecutionService $executionService,
        ServerProvider $serverProvider,
        Request $request,
        Session $session
    ): Response {
        $session->set('serverId', $request->request->get('serverId', $session->get('serverId', 1)));

        return $this->render('base/page/index.html.twig', [
            'articleList' => $this->getDoctrine()->getRepository(Article::class)->getLastArticles(),
            'playerListCount' => $executionService->getServerStatus()['players'] ?? 0,
            'isOnline' => (bool)$executionService->getServerStatus(),
            'admins' => $this->getDoctrine()->getRepository(AdminServerUser::class)->findBy(['serverId' => $serverProvider->getSessionServer()->getId()]),
            'itemList' => $this->getDoctrine()->getRepository(ItemList::class)->findBy(['serverId' => $serverProvider->getSessionServer()->getId()])
        ]);
    }

    /**
     * @Route(name="rule", path="/rule")
     */
    public function rule(RegulationRepository $repository): Response
    {
        return $this->render('base/page/rule.html.twig', [
            'ruleList' => $repository->getRegulationList(),
        ]);
    }
}
