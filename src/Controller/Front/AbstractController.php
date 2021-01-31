<?php

namespace ModernGame\Controller\Front;

use ModernGame\Util\VersionProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseAbstractController;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractController extends BaseAbstractController
{
    private VersionProvider $versionProvider;

    public function __construct(VersionProvider $versionProvider)
    {
        $this->versionProvider = $versionProvider;
    }

    protected function render(string $view, array $parameters = [], Response $response = null): Response
    {
        $newView = $this->versionProvider->getVersionOfView($view);

        return parent::render(
            $this->versionProvider->isVersionTwigExist($newView)
                ? $newView
                : $view,
            $parameters,
            $this->versionProvider->getCookieResponse()
        );
    }
}