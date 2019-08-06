<?php

namespace ModernGame\Service\Content;

use ModernGame\Database\Entity\Article;
use ModernGame\Database\Entity\User;
use ModernGame\Form\ArticleType;
use ModernGame\Validator\FormErrorHandler;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ArticleService
{
    private $form;
    private $formErrorHandler;
    /** @var User */
    private $user;

    public function __construct(
        FormFactoryInterface $form,
        FormErrorHandler $formErrorHandler,
        TokenStorageInterface $tokenStorage
    ) {
        $this->form = $form;
        $this->formErrorHandler = $formErrorHandler;
        $this->user = $tokenStorage->getToken()->getUser();
    }

    public function getMappedArticle(Request $request)
    {
        $article = new Article();
        $article->setAuthor($this->user->getId());

        $formArticle = $this->form->create(ArticleType::class, $article);

        $formArticle->handleRequest($request);
        $this->formErrorHandler->handle($formArticle);

        return $article;
    }
}
