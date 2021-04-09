<?php


namespace MNGame\Util;

use Doctrine\ORM\EntityManagerInterface;
use MNGame\Database\Entity\Server;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class DatabaseGlobalTwigExtension extends AbstractExtension implements GlobalsInterface
{
    protected EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getGlobals(): array
    {
        return [
            'server' => $this->em->getRepository(Server::class)->findAll()
        ];
    }
}