<?php

namespace ModernGame\Database\Repository;

use Doctrine\Persistence\ManagerRegistry;
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
            ->innerJoin(User::class, 'u', Join::WITH,'u.id = t.user')
            ->where('t.token = :token')
            ->andWhere('t.date > :currentDate')
            ->setParameters([
                ':token' => $token,
                ':currentDate' => date('Y-m-d H:i:s')
            ])
            ->setMaxResults(1);

        return $qb->getQuery()->getArrayResult()[0] ?? null;
    }

    public function insert($entity)
    {
        $tokens = $this->findBy(['user' => $entity->getUser()]);

        if (count($tokens) > 3) {
            $this->clearTokensInstances($tokens);
        }

        return parent::insert($entity);
    }

    private function clearTokensInstances($tokens)
    {
        /** @var Token $token */
        foreach ($tokens as $token) {
            if ($token->getDate()->format('Y-m-d H:i:s') < date('Y-m-d H:i:s')) {
                $this->delete($token->getToken());
            }
        }
    }
}
