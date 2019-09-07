<?php

namespace ModernGame\Controller\Content;

use ModernGame\Database\Entity\Article;
use Symfony\Component\HttpFoundation\JsonResponse;

class ArticleController
{
    public function getArticleList()
    {
        return new JsonResponse([
            'articleList' => $this->getDoctrine()
                ->getRepository(Article::class)->find()
        ]);
    }
}
