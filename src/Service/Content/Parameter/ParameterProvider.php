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
            $this->databaseParameterArrayObject[$parameter->name] = $parameter->getValue();
        }

        $this->databaseParameterArrayObject['server'] = $serverRepository->findAll();
    }

    public function getParameter(string $name)
    {
        return $this->databaseParameterArrayObject[$name] ?? $this->container->getParameter($name);
    }
}