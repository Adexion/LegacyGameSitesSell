<?php

namespace MNGame\Util;

use Doctrine\ORM\EntityManagerInterface;
use MNGame\Database\Entity\ModuleEnabled;
use MNGame\Database\Entity\Parameter;
use MNGame\Service\Route\ModuleProvider;
use MNGame\Database\Entity\Server;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class DatabaseGlobalTwigExtension extends AbstractExtension implements GlobalsInterface
{
    private EntityManagerInterface $em;
    private array $modules;

    public function __construct(EntityManagerInterface $em, ModuleProvider $moduleProvider)
    {
        $this->em = $em;
        $this->modules = $moduleProvider->getModules();
    }

    public function getGlobals(): array
    {
        $this->deactivateModuleLinks();

        return [
            'server' => $this->em->getRepository(Server::class)->findAll(),
            'global' => $this->em->getRepository(Parameter::class)->findAll(),
            'module' => $this->modules,
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