<?php

namespace ModernGame\Controller\Content;

use ModernGame\Database\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends AbstractController
{
    public function getArticleList()
    {
        return new JsonResponse([
            'articleList' => $this->getDoctrine()
                ->getRepository(Article::class)->findAll()
        ]);
    }

    public function getArticle(Request $request)
    {
        return new JsonResponse([
            'article' => $this->getDoctrine()
                ->getRepository(Article::class)->find($request->query->getInt('id'))
        ]);
    }
}
