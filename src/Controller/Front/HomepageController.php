<?php

namespace ModernGame\Controller\Front;

use ModernGame\Database\Entity\AdminServerUser;
use ModernGame\Database\Entity\Article;
use ModernGame\Database\Repository\RegulationRepository;
use ModernGame\Exception\ContentException;
use ModernGame\Service\Connection\Minecraft\RCONService;
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
        RCONService $RCONService,
        ServerProvider $serverProvider,
        Request $request,
        Session $session
    ): Response {
        $serverId = $request->request->get('serverId');
        if ($serverId) {
            $session->set('serverId', $serverId);
        }

        if (!$session->get('serverId')) {
            return $this->render('base/serverSelect.html.twig');
        }

        return $this->render('base/page/index.html.twig', [
            'articleList' => $this->getDoctrine()->getRepository(Article::class)->getLastArticles(),
            'playerListCount' => $RCONService->getServerStatus($serverProvider->getDefaultQueryServerId())['players'] ?? 0,
            'isOnline' => (bool)$RCONService->getServerStatus($serverProvider->getDefaultQueryServerId()),
            'playerList' => $RCONService->getPlayerList(),
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
