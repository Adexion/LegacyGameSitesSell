<?php

namespace MNGame\Database\Repository;

use Doctrine\Persistence\ManagerRegistry;
use MNGame\Database\Entity\AdminServerUser;

class AdminServerUserRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdminServerUser::class);
    }
}
