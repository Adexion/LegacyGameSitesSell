<?php

namespace ModernGame\Database\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

abstract class RepositoryAbstract extends ServiceEntityRepository
{
    /**
     * @param $entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update($entity)
    {
        $this->_em->merge($entity);
        $this->_em->flush();
    }

    /**
     * @param $entity
     *
     * @return object
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function insert($entity)
    {
        $this->_em->persist($entity);
        $this->_em->flush();

        return $entity;
    }

    public function delete(int $id)
    {
        $this->createQueryBuilder($this->getEntityName())
            ->delete()
            ->where('id = :id')
            ->setParameter(':id', $id)
            ->getQuery()->execute();
    }
}
