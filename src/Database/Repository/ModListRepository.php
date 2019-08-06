<?php

namespace ModernGame\Database\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use ModernGame\Database\Entity\ModList;

class ModListRepository extends RepositoryAbstract
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModList::class);
    }

    public function getModList()
    {
        return $this->createQueryBuilder('ml')
            ->select("ml.image, ml.name, ml.link")
            ->orderBy('ml.name', 'asc')
            ->getQuery()->execute();
    }
}
