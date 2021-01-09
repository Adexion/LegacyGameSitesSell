<?php

namespace ModernGame\Database\Repository;

use Doctrine\Persistence\ManagerRegistry;
use ModernGame\Database\Entity\AdminServerUser;

class AdminServerUserRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdminServerUser::class);
    }
}
