<?php

namespace MNGame\Controller\API;

use MNGame\Database\Repository\FAQRepository;
use MNGame\Database\Repository\TutorialRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TextController extends AbstractController
{
    /**
     * @Route(name="api-faq", path="/api/faq")
     */
    public function faq(FAQRepository $repository): Response
    {
        return new JsonResponse([
            'faqList' => $repository->findAll()
        ]);
    }

    /**
     * @Route(name="api-tutorial", path="/api/tutorial")
     */
    public function tutorial(TutorialRepository $repository): Response
    {
        return new JsonResponse([
            'tutorialList' => $repository->findAll()
        ]);
    }
}