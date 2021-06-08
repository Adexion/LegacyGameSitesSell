<?php

namespace MNGame\Service\Content;

use MNGame\Database\Entity\Article;
use MNGame\Database\Repository\ArticleRepository;
use MNGame\Exception\ContentException;
use MNGame\Form\HotPayType;
use MNGame\Service\AbstractService;
use MNGame\Serializer\CustomSerializer;
use MNGame\Service\ServiceInterface;
use MNGame\Validator\FormErrorHandler;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ArticleService extends AbstractService implements ServiceInterface
{
    private object $user;

    public function __construct(
        FormFactoryInterface $form,
        FormErrorHandler $formErrorHandler,
        TokenStorageInterface $tokenStorage,
        ArticleRepository $repository,
        CustomSerializer $serializer
    ) {
        $this->user = $tokenStorage->getToken()->getUser();

        parent::__construct($form, $formErrorHandler, $repository, $serializer);
    }

    /**
     * @throws ContentException
     */
    public function mapEntity(Request $request): Article
    {
        $request->request->set('author', $this->user->getUsername());

        return $this->map($request, new Article(), HotPayType::class);
    }

    /**
     * @throws ContentException
     */
    public function mapEntityById(Request $request): Article
    {
        return $this->mapById($request, HotPayType::class);
    }
}
