<?php

namespace ModernGame\Database\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;
use ModernGame\Database\Entity\Regulation;
use ModernGame\Database\Entity\RegulationCategory;

class RegulationRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Regulation::class);
    }
}
