<?php

namespace MNGame\Database\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use MNGame\Database\Entity\ResetPassword;
use MNGame\Database\Entity\User;

class ResetPasswordRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResetPassword::class);
    }

    /**
     * @param User $user
     * @param string $token
     * @throws ORMException
     *
     * @throws OptimisticLockException
     */
    public function addNewToken(User $user, string $token)
    {
        $resetPassword = new ResetPassword();

        $resetPassword->setToken($token);
        $resetPassword->setUser($user);

        $this->_em->persist($resetPassword);
        $this->_em->flush();
    }
}
