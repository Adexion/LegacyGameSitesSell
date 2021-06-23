<?php

namespace MNGame\Util;

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
    private EntityManagerInterface $em;
    private array $modules;
    private ?array $serverStatus;

    public function __construct(EntityManagerInterface $em, ModuleProvider $moduleProvider, ExecutionService $executionService)
    {
        $this->em = $em;
        $this->modules = $moduleProvider->getModules();
        $this->serverStatus = $executionService->getServerStatus();
    }

    public function getGlobals(): array
    {
        $this->deactivateModuleLinks();

        return [
            'server' => $this->em->getRepository(Server::class)->findAll(),
            'global' => $this->getGlobalVariables(),
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

    private function getGlobalVariables()
    {
        /** @var Parameter $param */
        foreach ($this->em->getRepository(Parameter::class)->findAll() as $param) {
            $paramList[$param->getName()] = $param->getValue();
        }

        return $paramList ?? [];
    }
}