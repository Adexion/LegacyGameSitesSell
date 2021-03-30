<?php

namespace MNGame\Database\Repository;

use Doctrine\Persistence\ManagerRegistry;
use MNGame\Database\Entity\Price;

class PriceRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Price::class);
    }
}
