<?php

namespace ModernGame\Controller\Front;

use ModernGame\Database\Entity\AdminServerUser;
use ModernGame\Database\Entity\Article;
use ModernGame\Database\Repository\ArticleRepository;
use ModernGame\Database\Repository\RegulationRepository;
use ModernGame\Exception\ContentException;
use ModernGame\Service\Connection\Minecraft\RCONService;
use ModernGame\Service\ServerProvider;
use ModernGame\Util\CookieProvider;
use ModernGame\Util\RandomHexGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    private const PLAYER_AVATAR = 'https://minotar.net/avatar/';
    private Response $cookieResponse;

    /**
     * @Route(name="index", path="/")
     *
     * @throws ContentException
     */
    public function index(
        RCONService $RCONService,
        ServerProvider $serverProvider,
        Request $request,
        Session $session,
        CookieProvider $cookieProvider
    ): Response {
        $serverId = $request->request->get('serverId');
        if ($serverId) {
            $session->set('serverId', $serverId);
        }

        if (!$session->get('serverId')) {
            return $this->render($cookieProvider->getVersionPrefix().'front/serverSelect.html.twig');
        }

        return $this->render($cookieProvider->getVersionPrefix().'front/page/index.html.twig', [
            'articleList' => $this->getDoctrine()->getRepository(Article::class)->getLastArticles(),
            'playerListCount' => $RCONService->getServerStatus($serverProvider->getDefaultQueryServerId())['players'] ?? 0,
            'isOnline' => (bool)$RCONService->getServerStatus($serverProvider->getDefaultQueryServerId()),
            'playerList' => $RCONService->getPlayerList(),
            'admins' => $this->getDoctrine()->getRepository(AdminServerUser::class)->findBy(['serverId' => $serverProvider->getSessionServer()]),
            'randomHexGenerator' => new RandomHexGenerator(),
        ], $this->cookieResponse ?? null);
    }

    /**
     * @Route(path="/articl/show/{slug}", name="show-article")
     */
    public function article(int $slug): Response
    {
        /** @var Article $article */
        $article = $this->getDoctrine()->getRepository(Article::class)->find($slug);

        return $this->render('front/page/article.html.twig', [
            'article' => $article,
            'avatar' => self::PLAYER_AVATAR.$article->getAuthor()->getUsername(),
        ]);
    }

    /**
     * @Route(name="rule", path="/rule")
     */
    public function rule(RegulationRepository $repository): Response
    {
        return $this->render('front/page/rule.html.twig', [
            'ruleList' => $repository->getRegulationList(),
        ]);
    }

    /**
     * @Route(path="/article/list/{slug}", name="show-article-list")
     */
    public function articleList(int $slug = 1): Response
    {
        /** @var Article[] articleList */
        $articleList = $this->getDoctrine()->getRepository(Article::class)->getArticles($slug);

        return $this->render('front/page/articleList.html.twig', [
            'articleList' => $articleList,
            'randomHexGenerator' => new RandomHexGenerator(),
            'count' => $this->getDoctrine()->getRepository(Article::class)->count([]),
            'perPages' => ArticleRepository::ARTICLE_PER_PAGES,
            'currentPage' => $slug,
        ]);
    }

    /**
     * @Route(path="/new", name="set-new-style")
     *
     * @throws ContentException
     */
    public function new(
        RCONService $RCONService,
        ServerProvider $serverProvider,
        Request $request,
        Session $session,
        CookieProvider $cookieProvider
    ): Response {
        $this->cookieResponse = new Response();
        $this->cookieResponse->headers->setCookie(new Cookie('new', !$request->cookies->get('new')));

        return $this->index($RCONService, $serverProvider, $request, $session, $cookieProvider);
    }
}
