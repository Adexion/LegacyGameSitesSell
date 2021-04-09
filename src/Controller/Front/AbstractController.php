<?php

namespace MNGame\Controller\Front;

use MNGame\Service\Content\Parameter\ParameterProvider;
use MNGame\Util\VersionProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseAbstractController;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractController extends BaseAbstractController
{
    private VersionProvider $versionProvider;
    private ParameterProvider $parameterProvider;

    public function __construct(VersionProvider $versionProvider, ParameterProvider $parameterProvider)
    {
        $this->versionProvider = $versionProvider;
        $this->parameterProvider = $parameterProvider;
    }

    protected function getParameter(string $name)
    {
        return $this->parameterProvider->getParameter($name);
    }

    protected function render(string $view, array $parameters = [], Response $response = null): Response
    {
        return parent::render(
            $this->versionProvider->getVersionOfView($view),
            $parameters,
            $this->versionProvider->getCookieResponse()
        );
    }
}