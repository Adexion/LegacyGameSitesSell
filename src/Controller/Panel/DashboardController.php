<?php

namespace ModernGame\Controller\Panel;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Controller\DashboardControllerInterface;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController implements DashboardControllerInterface
{
    /**
     * @Route("/panel", name="panel")
     * @Route("/admin", name="admin")
     */
    public function main(): Response
    {
        return $this->render('@ModernGame/panel/index.html.twig', [
            'dashboard_controller_filepath' => (new ReflectionClass(static::class))->getFileName(),
            'dashboard_controller_class' => (new ReflectionClass(static::class))->getShortName(),
        ]);
    }
}
