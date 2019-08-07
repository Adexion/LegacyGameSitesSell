<?php

namespace ModernGame\Database\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use ModernGame\Database\Entity\ResetPassword;

class ResetPasswordRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResetPassword::class);
    }

    /**
     * @param $id
     * @param string $token
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function addNewToken($id, string $token)
    {
        $resetPassword = new ResetPassword();

        $resetPassword->setToken($token);
        $resetPassword->setUserId($id);

        $this->_em->persist($resetPassword);
        $this->_em->flush();
    }
}
