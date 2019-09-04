<?php

namespace ModernGame\Service\Content;

use ModernGame\Database\Entity\Article;
use ModernGame\Database\Repository\ArticleRepository;
use ModernGame\Exception\ArrayException;
use ModernGame\Form\ArticleType;
use ModernGame\Service\Serializer;
use ModernGame\Validator\FormErrorHandler;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ArticleService
{
    private $form;
    private $formErrorHandler;
    private $user;
    private $repository;
    private $serializer;

    public function __construct(
        FormFactoryInterface $form,
        FormErrorHandler $formErrorHandler,
        TokenStorageInterface $tokenStorage,
        ArticleRepository $repository,
        Serializer $serializer
    ) {
        $this->form = $form;
        $this->formErrorHandler = $formErrorHandler;
        $this->user = $tokenStorage->getToken()->getUser();
        $this->repository = $repository;
        $this->serializer = $serializer;
    }

    /**
     * @throws ArrayException
     */
    public function mapArticle(Request $request)
    {
        $article = new Article();
        $article->setAuthor($this->user->getId());

        $formArticle = $this->form->create(ArticleType::class, $article);

        $formArticle->handleRequest($request);
        $this->formErrorHandler->handle($formArticle);

        return $article;
    }

    /**
     * @throws ArrayException
     */
    public function mapArticleById(Request $request)
    {
        $article = $this->repository->find($request->request->getInt('id'));

        if (empty($article)) {
            throw new ArrayException(['id' => 'Ta wartość jest nieprawidłowa.']);
        }

        $formArticle = $this->form->create(ArticleType::class, $article, ['method' => 'PUT']);

        $request->request->replace(
            $this->serializer->mergeDataWithEntity($article, $request->request->all())
        );

        $formArticle->handleRequest($request);
        $this->formErrorHandler->handle($formArticle);

        return $article;
    }
}
