<?php

namespace MNGame\Database\Repository;

use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use MNGame\Database\Entity\PaymentHistory;

class PaymentHistoryRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaymentHistory::class);
    }

    public function getStatistic($userId = null): array
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
            $userMoney = $statistic->getUser() ? $statistic->getUser()->getUsername() : 'Nie zalogowany';

            $statistics['moneyMonth'][$moneyMonth] = ($statistics['moneyMonth'][$moneyMonth] ?? 0) + $statistic->getAmount();
            $statistics['userMoney'][$userMoney] = ($statistics['userMoney'][$userMoney] ?? 0) + $statistic->getAmount();
        }

        return $statistics ?? [];
    }

    public function getThisMonthMoney(): float
    {
        $qb = $this->createQueryBuilder('ph')
            ->select('ph')
            ->where('MONTH(ph.date) = :date')
            ->setParameter('date', (new DateTime())->format('m'));

        /** @var PaymentHistory $statistic */
        foreach ($qb->getQuery()->execute() as $statistic) {
            $statistic = +$statistic->getAmount();
        }

        return $statistic ?? 0;
    }
}
