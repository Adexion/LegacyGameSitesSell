<?php

namespace ModernGame\Database\Repository;

use DateTime;
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
        foreach ($this->findAll() as $statistic) {
            if ($userId && $statistic->getUser()->getId() !== $userId) {
                continue;
            }

            $boughtName  = $statistic->getItemList()->getName();
            $userName = $statistic->getUser()->getUsername();
            $monthOfBought = (new DateTime($statistic->getDate()))->format('Y-m');

            $statistics['buyers'][$boughtName] = ($statistics['buyers'][$boughtName] ?? 0) + 1;
            $statistics['userBought'][$userName] = ($statistics['userBought'][$userName] ?? 0) + 1;
            $statistics['dateTime'][$monthOfBought] = ($statistics['dateTime'][$monthOfBought] ?? 0) + 1;
        }

        return $statistics ?? [];
    }
}
