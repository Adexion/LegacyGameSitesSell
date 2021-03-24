<?php

namespace ModernGame\Util;

use Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Loader\LoaderInterface;

class VersionProvider
{
    private LoaderInterface $loader;
    private ContainerInterface $container;

    private const OLD_VERSION = 'base';
    private ?string $version;
    private ?Request $request;

    public function __construct(RequestStack $requestStack, Environment $twig, ContainerInterface $container)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->loader = $twig->getLoader();
        $this->container = $container;
    }

    public function getVersionOfView(string $view): string
    {
        $this->getVersionByCookiesAndRequest();

        try {
            $first = $this->getVersionTwigPath($view, $this->version);
            if ($this->isVersionTwigExist($first)) {
                return $first;
            }
        } catch (Exception $ignored) {}

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

    private function getVersionByCookiesAndRequest()
    {
        $this->version = $this->request->query->getAlnum('version');
        if ($this->version) {
            return;
        }

        $this->version = $this->request->cookies->get('version', $this->container->getParameter('old'));
    }
}