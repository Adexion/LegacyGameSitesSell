<?php

namespace ModernGame\Service\Content;

use ModernGame\Database\Entity\Article;
use ModernGame\Database\Repository\ArticleRepository;
use ModernGame\Exception\ContentException;
use ModernGame\Form\ArticleType;
use ModernGame\Service\AbstractService;
use ModernGame\Serializer\CustomSerializer;
use ModernGame\Service\ServiceInterface;
use ModernGame\Validator\FormErrorHandler;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ArticleService extends AbstractService implements ServiceInterface
{
    private $user;

    public function __construct(
        FormFactoryInterface $form,
        FormErrorHandler $formErrorHandler,
        TokenStorageInterface $tokenStorage,
        ArticleRepository $repository,
        Serializer $serializer
    ) {
        $this->user = $tokenStorage->getToken()->getUser();

        parent::__construct($form, $formErrorHandler, $repository, $serializer);
    }

    /**
     * @throws ContentException
     */
    public function mapEntity(Request $request)
    {
        $article = new Article();
        $article->setAuthor($this->user->getId());

        return $this->map($request, $article, ArticleType::class);
    }

    /**
     * @throws ContentException
     */
    public function mapEntityById(Request $request)
    {
        return $this->mapById($request, ArticleType::class);
    }
}
