<?php

namespace ModernGame\Controller\Admin;

use ModernGame\Database\Entity\Article;
use ModernGame\Service\Content\ArticleService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class ArticleController extends AbstractAdminController
{
    protected const REPOSITORY_CLASS = Article::class;

    /**
     * @SWG\Tag(name="Admin/Article")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function postArticle(Request $request, ArticleService $service): JsonResponse
    {
        return $this->postEntity($request, $service);
    }

    /**
     * @SWG\Tag(name="Admin/Article")
     * @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     type="integer",
     *     required=false
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     *     @SWG\Items(ref=@Model(type=Article::class))
     * )
     */
    public function getEntity(Request $request): JsonResponse
    {
        return parent::getEntity($request);
    }

    /**
     * @SWG\Tag(name="Admin/Article")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function putArticle(Request $request, ArticleService $service): JsonResponse
    {
        return $this->putEntity($request, $service);
    }

    /**
     * @SWG\Tag(name="Admin/Article")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function deleteEntity(Request $request): JsonResponse
    {
        return parent::deleteEntity($request);
    }
}
