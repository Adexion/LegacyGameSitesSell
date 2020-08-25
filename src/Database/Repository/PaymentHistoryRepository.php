<?php

namespace ModernGame\Database\Repository;

use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use ModernGame\Database\Entity\PaymentHistory;

class PaymentHistoryRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaymentHistory::class);
    }

    public function getStatistic($userId = null)
    {
        $qb = $this->createQueryBuilder('ph')
            ->select('ph')
            ->where('ph.date >= :date')
            ->setParameter(':date', (new DateTime('-3 months'))->format('Y-m-d'));

        if ($userId) {
            $qb->andWhere('ph.user = :userId')
                ->setParameter(':userId', $userId);
        }

        /** @var PaymentHistory $statistic */
        foreach ($qb->getQuery()->execute() as $statistic) {
            $moneyMonth = (new DateTime($statistic->getDate()))->format('Y-m');
            $userMoney = $statistic->getUser()->getUsername();

            $statistics['moneyMonth'][$moneyMonth] = ($statistics['moneyMonth'][$moneyMonth] ?? 0) + $statistic->getAmount();
            $statistics['userMoney'][$userMoney] = ($statistics['userMoney'][$userMoney] ?? 0) + $statistic->getAmount();
        }

        return $statistics ?? [];
    }
}
