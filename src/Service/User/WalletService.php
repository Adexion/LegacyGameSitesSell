<?php

namespace MNGame\Service\User;

use Doctrine\ORM\EntityManagerInterface;
use MNGame\Database\Entity\User;
use MNGame\Database\Entity\Wallet;
use MNGame\Exception\ContentException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class WalletService
{
    private EntityManagerInterface $em;
    private AuthorizationCheckerInterface $authorizationChecker;

    public function __construct(EntityManagerInterface $em, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->em = $em;
        $this->authorizationChecker = $authorizationChecker;
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
        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            return $wallet->getCash();
        }

        $wallet->increaseCash($cash);

        if ($wallet->getCash() < 0) {
            throw new ContentException(['wallet' => 'Nie można wykonać operacji. Brak środkow na koncie.']);
        }

        $this->em->persist($wallet);
        $this->em->flush();

        return $wallet->getCash();
    }
}
