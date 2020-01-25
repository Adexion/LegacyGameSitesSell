<?php

namespace ModernGame\Database\Repository;

use Doctrine\Persistence\ManagerRegistry;
use ModernGame\Database\Entity\Ticket;
use ModernGame\Database\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

class TicketRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ticket::class);
    }

    public function getListGroup(UserInterface $user = null): array
    {
        $qb = $this
            ->createQueryBuilder('c')
            ->select('c.token', 'c.name')
            ->groupBy('c.token');

        if ($user instanceof User) {
            $qb
                ->where('c.user = :user')
                ->setParameter(':user', $user);
        }

        return $qb
            ->getQuery()
            ->execute();
    }
}
