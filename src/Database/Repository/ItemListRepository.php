<?php

namespace ModernGame\Database\Repository;

use Doctrine\Persistence\ManagerRegistry;
use ModernGame\Database\Entity\ItemList;

class ItemListRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItemList::class);
    }

    public function getSliderImages(): array
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('item_list.sliderImage, item_list.id, item_list.description, item_list.name, item_list.serverId')
            ->from(ItemList::class, 'item_list')
            ->orderBy('item_list.howManyBuyers', 'ASC')
            ->setMaxResults(4)
            ->getQuery()
            ->execute();
    }

    public function getAllList(): array
    {
        $list = $this->createQueryBuilder('item_list')
            ->select('item_list.id', 'item_list.name')
            ->getQuery()->execute();

        foreach ($list as $eq) {
            $mappedList[$eq['name']] = $eq['id'];
        }

        return $mappedList ?? [];
    }
}
