<?php

namespace MNGame\Database\Repository;

use Doctrine\Persistence\ManagerRegistry;
use MNGame\Database\Entity\RegulationCategory;

class RegulationCategoryRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RegulationCategory::class);
    }

    public function getCategoryList(): array
    {
        $rcList = $this->createQueryBuilder('rc')
            ->select('rc.id', 'rc.categoryName')
            ->getQuery()->execute();

        $categoryList = [];

        foreach ($rcList as $rc) {
            $categoryList[$rc['categoryName']] = $rc['id'];
        }

        return $categoryList;
    }
}
