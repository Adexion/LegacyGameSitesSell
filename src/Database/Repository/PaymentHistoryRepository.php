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
        /** @var PaymentHistory $statistic */
        foreach ($this->findAll() as $statistic) {
            if ($userId && $statistic->getUser()->getId() !== $userId) {
                continue;
            }

            $moneyMonth = (new DateTime($statistic->getDate()))->format('Y-m');
            $userMoney = $statistic->getUser()->getUsername();

            $statistics['moneyMonth'][$moneyMonth] = ($statistics['moneyMonth'][$moneyMonth] ?? 0) + $statistic->getAmount();
            $statistics['userMoney'][$userMoney] = ($statistics['userMoney'][$userMoney] ?? 0) + $statistic->getAmount();
        }

        return $statistics ?? [];
    }
}
