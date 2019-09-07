<?php

namespace ModernGame\Database\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use ModernGame\Database\Entity\Ticket;

class TicketRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ticket::class);
    }

    public function getListGroup()
    {
        return $this
            ->createQueryBuilder('c')
            ->groupBy('c.token')
            ->getQuery()
            ->execute();
    }
}
