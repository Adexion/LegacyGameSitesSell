<?php

namespace MNGame\Database\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;
use MNGame\Database\Entity\Regulation;
use MNGame\Database\Entity\RegulationCategory;

class RegulationRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Regulation::class);
    }

    public function getRegulationList(): array
    {
        /** @var Regulation $regulation */
        foreach ($this->findAll() as $regulation) {
            $regulationList[$regulation->getCategory()->getName()][] = $regulation;
        }

        return $regulationList ?? [];
    }
}
