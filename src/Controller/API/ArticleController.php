<?php

namespace MNGame\Controller\API;

use MNGame\Database\Entity\Article;
use MNGame\Database\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route(path="/api/article/list/{slug}", name="api-show-article-list", methods={"GET"})
     */
    public function getArticleList(int $slug = 1): Response
    {
        /** @var Article[] articleList */
        $articleList = $this->getDoctrine()->getRepository(Article::class)->getArticles($slug);

        return new JsonResponse([
            'articleList' => $articleList,
            'count' => $this->getDoctrine()->getRepository(Article::class)->count([]),
            'perPages' => ArticleRepository::ARTICLE_PER_PAGES,
            'currentPage' => $slug,
        ]);
    }

    /**
     * @Route(path="/api/article/show/{slug}", name="api-show-article", methods={"GET"})
     */
    public function getArticle(int $slug): Response
    {
        /** @var Article $article */
        $article = $this->getDoctrine()->getRepository(Article::class)->find($slug);

        return new JsonResponse([
            'article' => $article
        ]);
    }
}