<?php

namespace ModernGame\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

class AdditionalInfoService
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getAdditionalInfo()
    {
        return $this->container->getParameter('additionalInfo');
    }
}
