<?php

namespace ModernGame\Database\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use ModernGame\Database\Entity\Contact;

class ContactRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

    public function getListGroup()
    {
        return $this
            ->createQueryBuilder('c')
            ->groupBy('c.token')
            ->getQuery()
            ->execute();
    }
}
