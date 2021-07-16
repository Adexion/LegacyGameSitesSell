<?php

namespace MNGame\Service\Content\Parameter;

use MNGame\Database\Repository\ParameterRepository;
use MNGame\Database\Repository\ServerRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ParameterProvider
{
    private ContainerInterface $container;
    private array $parameters;
    private DatabaseParameterArrayObject $databaseParameterArrayObject;

    public function __construct(ParameterRepository $parameterRepository, ServerRepository $serverRepository, ContainerInterface $container)
    {
        $this->container = $container;
        $this->parameters = $parameterRepository->findAll();
        $this->databaseParameterArrayObject = new DatabaseParameterArrayObject();

        foreach ($this->parameters as $parameter) {
            if ($this->isParameterWithSameKeyExist($parameter->getName())) {
                $this->databaseParameterArrayObject[$parameter->name][] = $parameter->getValue();
                continue;
            }

            $this->databaseParameterArrayObject[$parameter->name] = $parameter->getValue();
        }

        $this->databaseParameterArrayObject['server'] = $serverRepository->findAll();
    }

    public function getParameter(string $name)
    {
        return $this->databaseParameterArrayObject[$name] ?? $this->container->getParameter($name);
    }

    public function getDatabaseParameters(): DatabaseParameterArrayObject
    {
        return $this->databaseParameterArrayObject;
    }

    private function isParameterWithSameKeyExist(string $name): bool
    {
        $count = 0;
        foreach ($this->parameters as $parameter) {
            if ($parameter->getName() === $name) {
                $count++;
            }
        }

        return $count > 1;
    }
}