<?php

namespace MNGame\Util;

use Doctrine\ORM\EntityManagerInterface;
use MNGame\Database\Entity\ModuleEnabled;
use MNGame\Database\Entity\Server;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class DatabaseGlobalTwigExtension extends AbstractExtension implements GlobalsInterface
{
    private EntityManagerInterface $em;
    private array $routes;

    public function __construct(EntityManagerInterface $em, RouterDataProvider $routerDataProvider)
    {
        $this->em = $em;
        $this->routes = $routerDataProvider->getRouteList();
    }

    public function getGlobals(): array
    {
        $this->deactivateModuleLinks();

        return [
            'server' => $this->em->getRepository(Server::class)->findAll(),
            'module' => $this->routes,
        ];
    }

    private function deactivateModuleLinks()
    {
        $modulesEnabledList = $this->em->getRepository(ModuleEnabled::class)->findAll();

        /** @var ModuleEnabled $moduleEnabled */
        foreach ($modulesEnabledList as $moduleEnabled) {
            if (!$moduleEnabled->isActive()) {
                unset($this->routes[$moduleEnabled->getRoute()]);
            }
        }
    }
}