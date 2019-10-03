<?php

namespace ModernGame\Database\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use ModernGame\Database\Entity\PaymentHistory;

class PaymentHistoryRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaymentHistory::class);
    }
}
