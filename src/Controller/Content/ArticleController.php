<?php

namespace ModernGame\Controller\Content;

use ModernGame\Database\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends AbstractController
{
    public function getArticle(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Article::class);
        $id = $request->query->getInt('id');

        return new JsonResponse(empty($id) ? $repository->findAll() : $repository->find($id));
    }
}
