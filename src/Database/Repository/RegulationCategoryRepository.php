<?php

namespace ModernGame\Database\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use ModernGame\Database\Entity\RegulationCategory;

class RegulationCategoryRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RegulationCategory::class);
    }

    /**
     * @return array
     */
    public function getCategoryList()
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
