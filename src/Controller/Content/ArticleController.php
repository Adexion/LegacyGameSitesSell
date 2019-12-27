<?php

namespace ModernGame\Controller\Content;

use ModernGame\Database\Entity\Article;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Swagger\Annotations as SWG;

class ArticleController extends AbstractController
{
    /**
     * Get articles for home page
     *
     * @SWG\Tag(name="Article")
     * @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     type="integer",
     *     required=false
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     *     @SWG\Schema(
     *          type="array",
     *          @SWG\Items(ref=@Model(type=Article::class))
     *     )
     * )
     */
    public function getArticle(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Article::class);
        $id = $request->query->getInt('id');

        return new JsonResponse(empty($id) ? $repository->findBy([], ['id' => 'DESC']) : [$repository->find($id)]);
    }
}
