<?php

namespace ModernGame\Database\Repository;

use Doctrine\Persistence\ManagerRegistry;
use ModernGame\Database\Entity\ModList;

class ModListRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModList::class);
    }

    public function getModList(): array
    {
        return $this->createQueryBuilder('ml')
            ->select("ml.image, ml.name, ml.link")
            ->orderBy('ml.name', 'asc')
            ->getQuery()->execute();
    }
}
