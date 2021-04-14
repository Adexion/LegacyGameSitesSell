<?php

namespace MNGame\Controller\Panel;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Controller\DashboardControllerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use MNGame\Exception\ContentException;
use MNGame\Predicate\RolePredicate;
use MNGame\Service\Connection\Client\ClientFactory;
use MNGame\Service\ServerProvider;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ConsoleController extends AbstractDashboardController implements DashboardControllerInterface
{
    use MainDashboardController;

    /**
     * @Route("/admin", name="admin")
     * @Route("/panel", name="panel")
     */
    public function index(): Response
    {
        return $this->render('@MNGame/panel/index.html.twig', [
            'dashboard_controller_filepath' => (new ReflectionClass(static::class))->getFileName(),
            'dashboard_controller_class' => (new ReflectionClass(static::class))->getShortName(),
        ]);
    }

    /**
     * @Route("/console", name="console")
     */
    public function console(ServerProvider $serverProvider, Security $security): Response
    {
        $serverList = RolePredicate::isAdminRoleGranted($security)
            ? $serverProvider->getServerList()
            : [$serverProvider->getServer($this->getUser()->getAssignedServerId())];

        return $this->render('@MNGame/panel/console.html.twig', [
            'dashboard_controller_filepath' => (new ReflectionClass(static::class))->getFileName(),
            'dashboard_controller_class' => (new ReflectionClass(static::class))->getShortName(),
            'serverList' => $serverList,
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
        $client = $clientFactory->create($serverProvider->getServer($request->request->get('id')));
        $client->sendCommand(trim($request->request->get('command'), '/'));

        return new Response();
    }
}
