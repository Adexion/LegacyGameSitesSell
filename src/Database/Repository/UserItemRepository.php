<?php

namespace ModernGame\Database\Repository;

use ModernGame\Database\Entity\UserItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class UserItemRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserItem::class);
    }

    public function addItem($item)
    {
        $this->getEntityManager()->persist($item);
        $this->getEntityManager()->flush();
    }

    public function deleteItem(UserItem $equipment)
    {
        if ($equipment->getQuantity() > 1) {
            $equipment->setQuantity($equipment->getQuantity() - 1);
            $this->getEntityManager()->persist($equipment);
            $this->getEntityManager()->flush();

            return;
        }

        $this->getEntityManager()->remove($equipment);
        $this->getEntityManager()->flush();
    }
}
