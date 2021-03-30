<?php

namespace MNGame\Database\Repository;

use MNGame\Database\Entity\User;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function search($id = null): array
    {
        $qb = $this
            ->createQueryBuilder('u')
            ->select('u.id, u.email, u.username, u.roles');

        if ($id) {
           $qb
               ->where('u.id = :id')
               ->setParameter(':id', $id);
        }

        return $qb
            ->getQuery()
            ->execute();
    }

    public function registerUser(User $user)
    {
        $this->_em->persist($user);
        $this->_em->flush($user);
    }
}
