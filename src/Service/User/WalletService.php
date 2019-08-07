<?php

namespace ModernGame\Service\User;

use Doctrine\ORM\EntityManagerInterface;
use ModernGame\Database\Entity\Wallet;
use ModernGame\Exception\ArrayException;

class WalletService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function create(int $userId): void
    {
        $wallet = new Wallet();

        $wallet->setUserId($userId);

        $this->em->persist($wallet);
        $this->em->flush();
    }

    /**
     * @throws ArrayException
     */
    public function changeCash(int $userId, float $cash): float
    {
        $wallet = $this->em->getRepository(Wallet::class)->findOneBy(['userId' => $userId]);
        $wallet->increaseCash($cash);

        if ($wallet->getCash() < 0) {
            throw new ArrayException(['wallet' => 'Nie można wykonać operacji. Brak środkow na koncie.']);
        }

        $this->em->persist($wallet);
        $this->em->flush();

        return $wallet->getCash();
    }
}
