<?php

namespace ModernGame\Controller\Admin;

use ModernGame\Database\Entity\Article;
use ModernGame\Database\Repository\ArticleRepository;
use ModernGame\Service\Content\ArticleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends AbstractController
{
    public function deleteArticle(Request $request)
    {
        /** @var ArticleRepository $articleRepository */
        $articleRepository = $this->getDoctrine()->getRepository(Article::class);

        $articleRepository->delete($request->request->getInt('id'));

        return new JsonResponse(null, Response::HTTP_OK);
    }

    public function putArticle(Request $request, ArticleService $articleService)
    {
        /** @var Article $article */
        $article = $articleService->getMappedArticle($request);

        /** @var ArticleRepository $articleRepository */
        $articleRepository = $this->getDoctrine()->getRepository(Article::class);
        $articleRepository->update($article);

        return new JsonResponse(null, Response::HTTP_OK);
    }

    public function postArticle(Request $request, ArticleService $articleService)
    {
        /** @var Article $article */
        $article = $articleService->getMappedArticle($request);

        /** @var ArticleRepository $articleRepository */
        $articleRepository = $this->getDoctrine()->getRepository(Article::class);
        $articleRepository->insert($article);

        return new JsonResponse(null, Response::HTTP_OK);
    }
}
