<?php

namespace ModernGame\Controller\Content;

use ModernGame\Database\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ArticleController extends AbstractController
{
    public function getArticleList()
    {
        return new JsonResponse([
            'articleList' => $this->getDoctrine()
                ->getRepository(Article::class)->findAll()
        ]);
    }
}
