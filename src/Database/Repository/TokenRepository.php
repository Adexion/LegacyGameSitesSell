<?php

namespace MNGame\Database\Repository;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;
use MNGame\Database\Entity\Token;
use MNGame\Database\Entity\User;

class TokenRepository extends AbstractRepository
{
    private const MAX_NUMBER_OF_TOKEN_INSTANCE = 3;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Token::class);
    }

    public function getTokenUsername($token): ?string
    {
        $qb = $this->createQueryBuilder('t')
            ->select('u.username')
            ->innerJoin(User::class, 'u', Join::WITH, 'u.id = t.user')
            ->where('t.token = :token')
            ->andWhere('t.date > :currentDate')
            ->setParameters([
                ':token' => $token,
                ':currentDate' => date('Y-m-d H:i:s')
            ])
            ->setMaxResults(1);

        return $qb->getQuery()->execute()[0]['username'] ?? null;
    }

    /**
     * @param object $entity
     * @return object
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function insert(object $entity): object
    {
        $tokens = $this->findBy(['user' => $entity->getUser()]);

        if (count($tokens) > self::MAX_NUMBER_OF_TOKEN_INSTANCE) {
            $this->clearTokensInstances($tokens);
        }

        return parent::insert($entity);
    }

    private function clearTokensInstances($tokens)
    {
        /** @var Token $token */
        foreach ($tokens as $token) {
            if ($token->getDate() < date('Y-m-d H:i:s')) {
                $this->delete($token->getToken());
            }
        }
    }
}
