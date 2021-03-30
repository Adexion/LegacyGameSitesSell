<?php

namespace MNGame\Database\Repository;

use Doctrine\Persistence\ManagerRegistry;
use MNGame\Database\Entity\Item;

class ItemRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }
}
