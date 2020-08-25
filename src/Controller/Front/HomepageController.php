<?php

namespace ModernGame\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /**
     * @Route(name="index", path="/")
     */
    public function index()
    {
        return $this->render('front/page/index.html.twig');
    }

    /**
     * @Route(name="rule", path="/rule")
     */
    public function rule()
    {
        return $this->render('front/page/rule.html.twig');
    }
}
