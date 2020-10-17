<?php

namespace ModernGame\Controller\Front;

use ModernGame\Controller\Backend\PlayerController;
use ModernGame\Database\Entity\Article;
use ModernGame\Service\Connection\Minecraft\RCONService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /**
     * @Route(name="index", path="/")
     */
    public function index(RCONService $RCONService)
    {
        return $this->render('front/page/index.html.twig', [
            'playerListCount' => count(explode(':', $RCONService->getPlayerList()))
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
            'avatar' => PlayerController::PLAYER_AVATAR . $article->getAuthor()->getUsername()
        ]);
    }

    /**
     * @Route(name="rule", path="/rule")
     */
    public function rule()
    {
        return $this->render('front/page/rule.html.twig');
    }
}
