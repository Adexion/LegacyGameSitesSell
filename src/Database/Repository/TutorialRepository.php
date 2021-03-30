<?php

namespace MNGame\Database\Repository;

use Doctrine\Persistence\ManagerRegistry;
use MNGame\Database\Entity\Tutorial;

class TutorialRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tutorial::class);
    }
}