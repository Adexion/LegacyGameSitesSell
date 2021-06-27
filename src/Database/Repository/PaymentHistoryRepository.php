<?php

namespace MNGame\Database\Repository;

use DateTime;
use MNGame\Enum\PaymentStatusEnum;
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
            if ($statistic->getPaymentStatus() !== PaymentStatusEnum::SUCCESS) {
                continue;
            }

            $moneyMonth = (new DateTime($statistic->getDate()))->format('Y-m');
            $userMoney = $statistic->getUser() ? $statistic->getUser()->getUsername() : 'Nie zalogowany';

            $stats['moneyMonth'][$moneyMonth] = ($stats['moneyMonth'][$moneyMonth] ?? 0) + $statistic->getAmount();
            $stats['userMoney'][$userMoney] = ($stats['userMoney'][$userMoney] ?? 0) + $statistic->getAmount();
        }

        return $stats ?? [];
    }

    public function getThisMonthMoney(): float
    {
        $qb = $this->createQueryBuilder('ph')
            ->select('ph')
            ->where('MONTH(ph.date) = :date')
            ->setParameter('date', (new DateTime())->format('m'));

        /** @var PaymentHistory $statistic */
        foreach ($qb->getQuery()->execute() as $statistic) {
            if ($statistic->getPaymentStatus() !== PaymentStatusEnum::SUCCESS) {
                continue;
            }

            $amount = +$statistic->getAmount();
        }

        return $amount ?? 0;
    }
}
