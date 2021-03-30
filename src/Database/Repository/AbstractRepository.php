<?php

namespace MNGame\Database\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

abstract class AbstractRepository extends ServiceEntityRepository
{
    /**
     * @param object $entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(object $entity)
    {
        $this->_em->merge($entity);
        $this->_em->flush();
    }

    /**
     * @param object $entity
     * @return object
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function insert(object $entity): object
    {
        $this->_em->persist($entity);
        $this->_em->flush();

        return $entity;
    }

    public function delete($id)
    {
        $entity = $this->find($id);

        $this->_em->remove($entity);
        $this->_em->flush();
    }
}
