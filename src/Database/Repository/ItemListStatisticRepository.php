<?php

namespace ModernGame\Database\Repository;

use Doctrine\Persistence\ManagerRegistry;
use ModernGame\Database\Entity\ItemListStatistic;

class ItemListStatisticRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItemListStatistic::class);
    }

    public function getStatistic($userId = null)
    {
        /** @var ItemListStatistic $statistic */
        foreach ($userId ? $this->findBy(['userId' => $userId]) : $this->findAll() as $statistic) {
            $statistics[] = [
                'dateTime' => $statistic->getDate(),
                'userId' => $statistic->getUser()->getId(),
                'userName' => $statistic->getUser()->getUsername(),
                'itemListId' => $statistic->getItemList()->getId(),
                'itemListName' => $statistic->getItemList()->getName()
            ];
        }

        return $statistics ?? [];
    }
}
