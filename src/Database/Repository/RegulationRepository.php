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

    public function getRegulation()
    {
       $builder = $this->_em->createQueryBuilder();

        $builder
           ->select('reg.description, cat.categoryName as category')
           ->from(Regulation::class, 'reg')
           ->innerJoin(RegulationCategory::class, 'cat',Join::WITH, 'cat.id = reg.categoryId')
           ->orderBy('cat.id','ASC');

       return $builder->getQuery()->execute();
    }
}