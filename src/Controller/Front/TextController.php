<?php

namespace MNGame\Controller\Front;

use MNGame\Database\Repository\FAQRepository;
use MNGame\Database\Repository\TutorialRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TextController extends AbstractController
{
    /**
     * @Route(name="faq", path="/faq")
     */
    public function faq(FAQRepository $repository): Response
    {
        return $this->render('base/page/faq.html.twig', [
            'faqList' => $repository->findAll()
        ]);
    }

    /**
     * @Route(name="tutorial", path="/tutorial")
     */
    public function tutorial(TutorialRepository $repository): Response
    {
        return $this->render('base/page/tutorial.twig', [
            'tutorialList' => $repository->findAll()
        ]);
    }
}