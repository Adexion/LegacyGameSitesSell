<?php

namespace ModernGame\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DownloadController extends AbstractController
{
    /**
     * @Route(name="download", path="/download")
     */
    public function download()
    {
        return $this->render('front/page/download.html.twig', $this->getParameter('download'));
    }
}
