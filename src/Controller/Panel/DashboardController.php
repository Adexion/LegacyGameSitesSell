<?php

namespace ModernGame\Controller\Panel;

use ReflectionClass;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/panel/", name="panel")
     */
    public function index(): Response
    {
        return $this->render('panel/index.html.twig', [
            'dashboard_controller_filepath' => (new ReflectionClass(static::class))->getFileName(),
            'dashboard_controller_class' => (new ReflectionClass(static::class))->getShortName(),
        ]);
    }
}
