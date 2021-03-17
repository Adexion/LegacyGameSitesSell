<?php

namespace ModernGame\Controller\Front;

use ModernGame\Database\Entity\AdminServerUser;
use ModernGame\Database\Entity\Article;
use ModernGame\Database\Repository\RegulationRepository;
use ModernGame\Exception\ContentException;
use ModernGame\Service\Connection\Minecraft\ExecutionService;
use ModernGame\Service\ServerProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /**
     * @Route(name="index", path="/")
     *
     * @throws ContentException
     */
    public function index(
        ExecutionService $executionService,
        ServerProvider $serverProvider,
        Request $request,
        Session $session
    ): Response {
        $session->set('serverId', $request->request->get('serverId', 1));

        return $this->render('base/page/index.html.twig', [
            'articleList' => $this->getDoctrine()->getRepository(Article::class)->getLastArticles(),
            'playerListCount' => $executionService->getServerStatus($serverProvider->getDefaultQueryServerId())['players'] ?? 0,
            'isOnline' => (bool)$executionService->getServerStatus($serverProvider->getDefaultQueryServerId()),
            'playerList' => $executionService->getPlayerList(),
            'admins' => $this->getDoctrine()->getRepository(AdminServerUser::class)->findBy(['serverId' => $serverProvider->getSessionServer()])
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
