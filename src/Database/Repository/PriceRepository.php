<?php

namespace ModernGame\Database\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use ModernGame\Database\Entity\Price;

class PriceRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Price::class);
    }
}
