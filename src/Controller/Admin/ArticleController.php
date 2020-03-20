<?php

namespace ModernGame\Controller\Admin;

use ModernGame\Database\Entity\Article;
use ModernGame\Serializer\CustomSerializer;
use ModernGame\Service\Content\ArticleService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends AbstractAdminController
{
    protected const REPOSITORY_CLASS = Article::class;

    /**
     * Add new article
     *
     * @SWG\Tag(name="Admin/Article")
     * @SWG\Parameter(
     *     name="JSON",
     *     in="body",
     *     type="object",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(property="title", type="string"),
     *          @SWG\Property(property="subhead", type="string"),
     *          @SWG\Property(property="image", type="string"),
     *          @SWG\Property(property="text", type="string"),
     *          @SWG\Property(property="shortText", type="string")
     *     )
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Evertythig works",
     * )
     */
    public function postArticle(Request $request, ArticleService $service): JsonResponse
    {
        return $this->postEntity($request, $service);
    }

    /**
     * Get Article list
     *
     * Get article list or one article when id given
     *
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
    public function getEntity(Request $request, CustomSerializer $serializer): JsonResponse
    {
        return parent::getEntity($request, $serializer);
    }

    /**
     * Update article
     *
     * @SWG\Tag(name="Admin/Article")
     * @SWG\Parameter(
     *     name="JSON",
     *     in="body",
     *     type="object",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(property="id", type="integer"),
     *          @SWG\Property(property="title", type="string"),
     *          @SWG\Property(property="subhead", type="string"),
     *          @SWG\Property(property="image", type="string"),
     *          @SWG\Property(property="text", type="string"),
     *          @SWG\Property(property="shortText", type="string")
     *     )
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Evertythig works",
     * )
     */
    public function putArticle(Request $request, ArticleService $service): JsonResponse
    {
        return $this->putEntity($request, $service);
    }

    /**
     * Delete article
     *
     * Delete article from specific id
     *
     * @SWG\Tag(name="Admin/Article")
     * @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     type="integer",
     *     required=false
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Evertythig works",
     * )
     */
    public function deleteEntity(Request $request): JsonResponse
    {
        return parent::deleteEntity($request);
    }
}
