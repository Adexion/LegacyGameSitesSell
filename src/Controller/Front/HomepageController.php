<?php

namespace ModernGame\Controller\Front;

use ModernGame\Controller\Backend\PlayerController;
use ModernGame\Database\Entity\Article;
use ModernGame\Database\Repository\RegulationRepository;
use ModernGame\Service\Connection\Minecraft\RCONService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /**
     * @Route(name="index", path="/")
     */
    public function index(RCONService $RCONService): Response
    {
        return $this->render('front/page/index.html.twig', [
            'articleList' => $this->getDoctrine()->getRepository(Article::class)->getLastArticles(),
            'playerListCount' => $RCONService->getServerStatus()['players'] ?? 0,
            'isOnline' => (bool)$RCONService->getServerStatus(),
        ]);
    }

    /**
     * @Route(path="/article/{slug}", name="show-article")
     */
    public function article(string $slug)
    {
        /** @var Article $article */
        $article = $this->getDoctrine()->getRepository(Article::class)->find($slug);

        return $this->render('front/page/article.html.twig', [
            'article' => $article,
            'avatar' => PlayerController::PLAYER_AVATAR.$article->getAuthor()->getUsername(),
        ]);
    }

    /**
     * @Route(name="rule", path="/rule")
     */
    public function rule(RegulationRepository $repository)
    {
        return $this->render('front/page/rule.html.twig', [
            'ruleList' => $repository->getRegulationList(),
        ]);
    }
}
