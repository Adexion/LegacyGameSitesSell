<?php

namespace MNGame\Controller\Front;

use MNGame\Database\Entity\Article;
use MNGame\Database\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    private const PLAYER_AVATAR = 'https://cravatar.eu/avatar/';

    /**
     * @Route(path="/article/show/{slug}", name="show-article")
     */
    public function article(int $slug): Response
    {
        /** @var Article $article */
        $article = $this->getDoctrine()->getRepository(Article::class)->find($slug);

        return $this->render('base/page/article.html.twig', [
            'article' => $article,
            'avatar' => self::PLAYER_AVATAR.$article->getAuthor()->getUsername(),
        ]);
    }

    /**
     * @Route(path="/article/list/{slug}", name="show-article-list")
     */
    public function articleList(int $slug = 1): Response
    {
        /** @var Article[] articleList */
        $articleList = $this->getDoctrine()->getRepository(Article::class)->getArticles($slug);

        return $this->render('base/page/articleList.html.twig', [
            'articleList' => $articleList,
            'count' => $this->getDoctrine()->getRepository(Article::class)->count([]),
            'perPages' => ArticleRepository::ARTICLE_PER_PAGES,
            'currentPage' => $slug,
        ]);
    }
}