<?php

namespace ModernGame\Database\Repository;

use ModernGame\Database\Entity\UserItem;
use Doctrine\Persistence\ManagerRegistry;

class UserItemRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserItem::class);
    }

    public function deleteItem(UserItem $item)
    {
        if ($item->getQuantity() > 1) {
            $item->setQuantity($item->getQuantity() - 1);
            $this->getEntityManager()->persist($item);
            $this->getEntityManager()->flush();

            return;
        }

        $this->getEntityManager()->remove($item);
        $this->getEntityManager()->flush();
    }
}
