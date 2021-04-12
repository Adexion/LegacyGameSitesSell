<?php

namespace MNGame\Service\Route;

use MNGame\Database\Repository\ModuleEnabledRepository;

class ModuleRouteBuilder
{
    private ModuleEnabledRepository $moduleEnabledRepository;
    private RouterDataProvider $routerDataProvider;

    public function __construct(
        ModuleEnabledRepository $moduleEnabledRepository,
        RouterDataProvider $routerDataProvider
    ) {
        $this->moduleEnabledRepository = $moduleEnabledRepository;
        $this->routerDataProvider = $routerDataProvider;
    }

    public function getData(): array
    {
        $moduleLinks = [];
        foreach ($this->moduleEnabledRepository->findAll() as $module){
            $foundedModule[$module->getRoute()] = $module->isActive();
        }

        foreach ($this->routerDataProvider->getRouteList() as $key => $value) {
            if (isset($foundedModule[$key]) && !$foundedModule[$key]) {
                continue;
            }

            $moduleLinks = array_merge($moduleLinks, $value['menuLinks']);
        }

        return $moduleLinks;
    }
}