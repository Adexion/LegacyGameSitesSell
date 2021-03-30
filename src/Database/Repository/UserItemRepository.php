<?php

namespace MNGame\Database\Repository;

use MNGame\Database\Entity\Item;
use MNGame\Database\Entity\User;
use MNGame\Database\Entity\UserItem;
use Doctrine\Persistence\ManagerRegistry;

class UserItemRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserItem::class);
    }

    public function createItem(User $user, Item $item)
    {
        $userItem = new UserItem();

        $userItem->setUser($user);
        $userItem->setItem($item);
        $userItem->setQuantity(1);
        $userItem->setName($item->getName());
        $userItem->setIcon($item->getIcon());
        $userItem->setCommand($item->getCommand());

        return $this->insert($userItem);
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
