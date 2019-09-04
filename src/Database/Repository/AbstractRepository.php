<?php

namespace ModernGame\Database\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

abstract class AbstractRepository extends ServiceEntityRepository
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
        $entity = $this->find($id);

        $this->_em->remove($entity);
        $this->_em->flush();
    }
}
