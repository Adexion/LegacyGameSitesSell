<?php

namespace ModernGame\Database\Repository;

use ModernGame\Database\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class UserRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function registerUser(User $user)
    {
        $this->_em->persist($user);
        $this->_em->flush($user);
    }
}
