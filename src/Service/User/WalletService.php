<?php

namespace ModernGame\Service\User;

use Doctrine\ORM\EntityManagerInterface;
use ModernGame\Database\Entity\User;
use ModernGame\Database\Entity\Wallet;
use ModernGame\Exception\ContentException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class WalletService
{
    private $em;
    private $user;

    public function __construct(EntityManagerInterface $em, TokenStorageInterface $token)
    {
        $this->em = $em;
        $this->user = $token->getToken()->getUser();
    }

    public function create(User $user): void
    {
        $wallet = new Wallet();

        $wallet->setUser($user);

        $this->em->persist($wallet);
        $this->em->flush();
    }

    /**
     * @throws ContentException
     */
    public function changeCash(float $cash): float
    {
        $wallet = $this->em->getRepository(Wallet::class)->findOneBy(['user' => $this->user]);
        $wallet->increaseCash($cash);

        if ($wallet->getCash() < 0) {
            throw new ContentException(['wallet' => 'Nie można wykonać operacji. Brak środkow na koncie.']);
        }

        $this->em->persist($wallet);
        $this->em->flush();

        return $wallet->getCash();
    }
}
