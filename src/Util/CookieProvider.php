<?php

namespace ModernGame\Util;

use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\RequestStack;

class CookieProvider
{
    private InputBag $cookies;

    public function __construct(RequestStack $requestStack)
    {
        $this->cookies = $requestStack->getCurrentRequest()->cookies;
    }

    public function getVersionPrefix(): string
    {
        return $this->cookies->get('new') ? 'new/' : '';
    }

    public function isNewVersionSer(): bool
    {
        return $this->cookies->get('new');
    }
}