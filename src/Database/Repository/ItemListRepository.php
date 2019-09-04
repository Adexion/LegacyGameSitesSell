<?php

namespace ModernGame\Database\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use ModernGame\Database\Entity\ItemList;

class ItemListRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItemList::class);
    }

    public function getSliderImages()
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('item_list.sliderImage, item_list.id, item_list.description, item_list.name')
            ->from(ItemList::class, 'item_list')
            ->orderBy('item_list.howManyBuyers', 'ASC')
            ->setMaxResults(4)
            ->getQuery()
            ->execute();
    }

    public function getAllList()
    {
        $list = $this->createQueryBuilder('item_list')
            ->select('item_list.id', 'item_list.name')
            ->getQuery()->execute();

        $mappedList = [];

        foreach ($list as $eq) {
            $mappedList[$eq['name']] = $eq['id'];
        }

        return $mappedList;
    }

    public function increaseCounterOfBuying(int $id)
    {
        /** @var ItemList $itemList */
        $itemList = $this->find($id);
        $itemList->increaseCounterOfBuying();

        $this->getEntityManager()->flush($itemList);
    }
}
