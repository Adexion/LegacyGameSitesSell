<?php

namespace ModernGame\Controller\Panel;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Controller\DashboardControllerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use ModernGame\Exception\ContentException;
use ModernGame\Service\Connection\Client\ClientFactory;
use ModernGame\Service\ServerProvider;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController implements DashboardControllerInterface
{
    use MainDashboardController;

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
     * @throws ContentException
     */
    public function sendCommand(Request $request,  ClientFactory $clientFactory, ServerProvider $serverProvider): Response
    {
        $client = $clientFactory->create(
            $serverProvider->getServer($request->request->getInt('serverId'))
        );
        $client->sendCommand(trim($request->request->get('command'), '/'));

        return new Response();
    }
}
