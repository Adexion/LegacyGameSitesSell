<?php

namespace MNGame\Controller\Panel;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Controller\DashboardControllerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use MNGame\Exception\ContentException;
use MNGame\Service\Connection\Client\ClientFactory;
use MNGame\Service\ServerProvider;
use ReflectionClass;
use ReflectionException;
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
    public function main(ServerProvider $serverProvider): Response
    {
        return $this->render('@MNGame/panel/index.html.twig', [
            'dashboard_controller_filepath' => (new ReflectionClass(static::class))->getFileName(),
            'dashboard_controller_class' => (new ReflectionClass(static::class))->getShortName(),
            'server' => $serverProvider->getServer($serverProvider->getDefaultConnectionServerId()),
        ]);
    }

    /**
     * @Route("/panel/command", name="panel-command")
     *
     * @throws ContentException
     * @throws ReflectionException
     */
    public function sendCommand(Request $request, ClientFactory $clientFactory, ServerProvider $serverProvider): Response
    {
        $client = $clientFactory->create(
            $serverProvider->getServer($serverProvider->getDefaultConnectionServerId())
        );
        $client->sendCommand(trim($request->request->get('command'), '/'));

        return new Response();
    }
}
