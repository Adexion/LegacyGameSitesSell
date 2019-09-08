<?php

namespace ModernGame\Database\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;
use ModernGame\Database\Entity\Token;
use ModernGame\Database\Entity\User;

class TokenRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Token::class);
    }

    public function getTokenUsername($token)
    {
        $qb = $this->createQueryBuilder('t')
            ->select('u.username')
            ->innerJoin(User::class, 'u', Join::WITH,'u.id = t.userId')
            ->where('t.token = :token')
            ->andWhere('t.date > :currentDate')
            ->setParameters([
                ':token' => $token,
                ':currentDate' => date('Y-m-d H:i:s')
            ])
            ->setMaxResults(1);

        return $qb->getQuery()->execute()[0];
    }
}