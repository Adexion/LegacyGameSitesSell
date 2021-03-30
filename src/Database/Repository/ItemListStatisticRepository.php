<?php

namespace MNGame\Database\Repository;

use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use MNGame\Database\Entity\ItemListStatistic;

class ItemListStatisticRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItemListStatistic::class);
    }

    public function getStatistic($userId = null)
    {
        $qb = $this->createQueryBuilder('ils')
            ->select('ils')
            ->where('ils.date >= :date')
            ->setParameter(':date', (new DateTime('-1 month'))->format('Y-m-d'));

        if ($userId) {
            $qb->andWhere('ils.user = :userId')
                ->setParameter(':userId', $userId);
        }

        /** @var ItemListStatistic $statistic */
        foreach ($qb->getQuery()->execute() as $statistic) {
            $boughtName = $statistic->getItemList()->getName();
            $userName = $statistic->getUser()->getUsername();
            $monthOfBought = (new DateTime($statistic->getDate()))->format('Y-m');

            $statistics['buyers'][$boughtName] = ($statistics['buyers'][$boughtName] ?? 0) + 1;
            $statistics['userBought'][$userName] = ($statistics['userBought'][$userName] ?? 0) + 1;
            $statistics['dateTime'][$monthOfBought] = ($statistics['dateTime'][$monthOfBought] ?? 0) + 1;
        }

        return $statistics ?? [];
    }
}
