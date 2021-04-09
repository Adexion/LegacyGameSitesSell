<?php

namespace MNGame\Util;

use Exception;
use MNGame\Service\Content\Parameter\ParameterProvider;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Loader\LoaderInterface;

class VersionProvider
{
    private LoaderInterface $loader;
    private ParameterProvider $container;

    private const OLD_VERSION = 'base';
    private ?string $version;
    private ?Request $request;

    public function __construct(RequestStack $requestStack, Environment $twig, ParameterProvider $container)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->loader = $twig->getLoader();
        $this->container = $container;

        $this->version = $this->request->query->getAlnum('version');
        if ($this->version) {
            return;
        }

        $this->version = $this->request->cookies->get('version', 'latest');
    }

    public function getVersionOfView(string $view): string
    {
        try {
            $first = $this->getVersionTwigPath($view, $this->version);
            if ($this->isVersionTwigExist($first)) {
                return $first;
            }
        } catch (Exception $ignored) {
        }

        $old = $this->getVersionTwigPath($view, 'old');
        if ($this->isVersionTwigExist($old)) {
            return $old;
        }

        return $view;
    }

    public function getVersionTwigPath($view, $version)
    {
        return str_replace(self::OLD_VERSION, $this->container->getParameter($version), $view);
    }

    public function isVersionTwigExist(string $view): bool
    {
        return $this->loader->exists($view);
    }

    public function getCookieResponse(): Response
    {
        $response = new Response();
        $response->headers->setCookie(new Cookie('version', $this->version));

        return $response;
    }
}