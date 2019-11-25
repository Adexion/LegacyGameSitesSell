<?php

namespace ModernGame\Controller\Admin;

use ModernGame\Database\Entity\Article;
use ModernGame\Database\Repository\ArticleRepository;
use ModernGame\Exception\ContentException;
use ModernGame\Service\Content\ArticleService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;

class ArticleController extends AbstractController
{
    /**
     * @SWG\Tag(name="Admin/Article")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
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

    /**
     * @SWG\Tag(name="Admin/Article")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function putArticle(Request $request, ArticleService $articleService)
    {
        /** @var Article $article */
        $article = $articleService->mapEntityById($request);

        /** @var ArticleRepository $articleRepository */
        $articleRepository = $this->getDoctrine()->getRepository(Article::class);
        $articleRepository->update($article);

        return new JsonResponse(null, Response::HTTP_OK);
    }

    /**
     * @SWG\Tag(name="Admin/Article")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function postArticle(Request $request, ArticleService $articleService)
    {
        /** @var Article $article */
        $article = $articleService->mapEntity($request);

        /** @var ArticleRepository $articleRepository */
        $articleRepository = $this->getDoctrine()->getRepository(Article::class);
        $articleRepository->insert($article);

        return new JsonResponse(null, Response::HTTP_OK);
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
    public function getArticle(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Article::class);
        $id = $request->query->getInt('id');

        return new JsonResponse(empty($id) ? $repository->findAll() : $repository->find($id));
    }
}
