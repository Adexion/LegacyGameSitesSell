<?php

namespace MNGame\Service\Route;

use MNGame\Database\Repository\ModuleEnabledRepository;

class ModuleRouteBuilder
{
    private ModuleEnabledRepository $moduleEnabledRepository;
    private ModuleProvider $routerDataProvider;

    public function __construct(
        ModuleEnabledRepository $moduleEnabledRepository,
        ModuleProvider $routerDataProvider
    ) {
        $this->moduleEnabledRepository = $moduleEnabledRepository;
        $this->routerDataProvider = $routerDataProvider;
    }

    public function getData(): array
    {
        $moduleLinks = [];
        foreach ($this->moduleEnabledRepository->findAll() as $module){
            $foundedModule[$module->getName()] = $module->isActive();
        }

        foreach ($this->routerDataProvider->getModules() as $key => $value) {
            if (isset($foundedModule[$key]) && !$foundedModule[$key]) {
                continue;
            }

            if (!isset($value['menuLinks'])) {
                continue;
            }

            $moduleLinks = array_merge($moduleLinks, $value['menuLinks']);
        }

        return $moduleLinks;
    }
}