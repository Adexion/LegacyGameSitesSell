<?php

namespace ModernGame\Database\Repository;

use Doctrine\Persistence\ManagerRegistry;
use ModernGame\Database\Entity\FAQ;

class FAQRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FAQ::class);
    }
}
