<?php

namespace MNGame\Util;

use MNGame\Service\Content\Parameter\ParameterProvider;
use MNGame\Service\ServerProvider;
use Doctrine\ORM\EntityManagerInterface;
use MNGame\Database\Entity\ModuleEnabled;
use MNGame\Database\Entity\Parameter;
use MNGame\Service\Route\ModuleProvider;
use MNGame\Database\Entity\Server;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use MNGame\Service\Minecraft\ExecutionService;

class GlobalTwigExtension extends AbstractExtension implements GlobalsInterface
{
    private array $modules;
    private ?array $serverStatus;
    private ParameterProvider $parameterProvider;
    private EntityManagerInterface $em;

    public function __construct(ModuleProvider $moduleProvider, ExecutionService $executionService, ParameterProvider $parameterProvider, EntityManagerInterface $entityManager)
    {
        $this->modules = $moduleProvider->getModules();
        $this->serverStatus = $executionService->getServerStatus();
        $this->parameterProvider = $parameterProvider;
        $this->em = $entityManager;
    }

    public function getGlobals(): array
    {
        $this->deactivateModuleLinks();

        return [
            'server' => $this->em->getRepository(Server::class)->findAll(),
            'global' => $this->parameterProvider->getDatabaseParameters(),
            'module' => $this->modules,
            'isOnline' => (bool)$this->serverStatus,
            'playerListCount' => $this->serverStatus['players'] ?? 0,
        ];
    }

    private function deactivateModuleLinks()
    {
        $modulesEnabledList = $this->em->getRepository(ModuleEnabled::class)->findAll();

        /** @var ModuleEnabled $moduleEnabled */
        foreach ($modulesEnabledList as $moduleEnabled) {
            if (!$moduleEnabled->isActive()) {
                unset($this->modules[$moduleEnabled->getName()]);
            }
        }
    }
}