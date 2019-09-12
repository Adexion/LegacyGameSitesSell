<?php

namespace ModernGame\Controller\Admin;

use ModernGame\Database\Entity\Article;
use ModernGame\Database\Repository\ArticleRepository;
use ModernGame\Exception\ContentException;
use ModernGame\Service\Content\ArticleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends AbstractController
{
    public function deleteArticle(Request $request)
    {
        if (empty($request->query->getInt('id'))) {
            throw new ContentException(['id' => 'Ta wartość nie może być pusta.']);
        }

        /** @var ArticleRepository $articleRepository */
        $articleRepository = $this->getDoctrine()->getRepository(Article::class);
        $articleRepository->delete($request->query->getInt('id'));

        return new JsonResponse(null, Response::HTTP_OK);
    }

    public function putArticle(Request $request, ArticleService $articleService)
    {
        /** @var Article $article */
        $article = $articleService->mapEntityById($request);

        /** @var ArticleRepository $articleRepository */
        $articleRepository = $this->getDoctrine()->getRepository(Article::class);
        $articleRepository->update($article);

        return new JsonResponse(null, Response::HTTP_OK);
    }

    public function postArticle(Request $request, ArticleService $articleService)
    {
        /** @var Article $article */
        $article = $articleService->mapEntity($request);

        /** @var ArticleRepository $articleRepository */
        $articleRepository = $this->getDoctrine()->getRepository(Article::class);
        $articleRepository->insert($article);

        return new JsonResponse(null, Response::HTTP_OK);
    }

    public function getArticle(Request $request)
    {
        return new JsonResponse(
            $this->getDoctrine()->getRepository(Article::class)->find($request->query->getInt('id'))
        );
    }

    public function getArticles()
    {
        return new JsonResponse(
            $this->getDoctrine()->getRepository(Article::class)->findAll()
        );
    }
}
