<?php

namespace ModernGame\Database\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use ModernGame\Database\Entity\ItemListStatistic;

class ItemListStatisticRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItemListStatistic::class);
    }
}
