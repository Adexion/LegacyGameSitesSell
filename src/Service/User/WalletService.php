<?php

namespace ModernGame\Service\User;

use Doctrine\ORM\EntityManagerInterface;
use ModernGame\Database\Entity\User;
use ModernGame\Database\Entity\Wallet;
use ModernGame\Exception\ContentException;
use Symfony\Component\Security\Core\User\UserInterface;

class WalletService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function create(User $user): void
    {
        $wallet = new Wallet();

        $wallet->setUser($user);
        $wallet->setCash(2);

        $this->em->persist($wallet);
        $this->em->flush();
    }

    /**
     * @throws ContentException
     */
    public function changeCash(float $cash, UserInterface $user): float
    {
        $wallet = $this->em->getRepository(Wallet::class)->findOneBy(['user' => $user]);
        $wallet->increaseCash($cash);

        if ($wallet->getCash() < 0) {
            throw new ContentException(['wallet' => 'Nie można wykonać operacji. Brak środkow na koncie.']);
        }

        $this->em->persist($wallet);
        $this->em->flush();

        return $wallet->getCash();
    }
}
