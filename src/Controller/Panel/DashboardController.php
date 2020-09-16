<?php

namespace ModernGame\Controller\Panel;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Controller\DashboardControllerInterface;
use ModernGame\Service\Connection\Minecraft\RCONService;
use ModernGame\Service\Connection\Minecraft\ServerConnectionService;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("/panel/command", name="panel-command")
     */
    public function sendCommand(Request $request,  ServerConnectionService $connectionService)
    {
        $client = $connectionService->getClient();
        $client->sendCommand(trim($request->request->get('command'), '/'));

        return new Response();
    }
}
