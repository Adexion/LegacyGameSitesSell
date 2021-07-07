<?php

namespace MNGame\Service\User;

use MNGame\Database\Entity\User;
use MNGame\Database\Entity\Wallet;
use MNGame\Exception\ContentException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class WalletService
{
    private EntityManagerInterface $em;
    private AuthorizationCheckerInterface $authorizationChecker;
    private string|\Stringable|UserInterface $user;

    public function __construct(EntityManagerInterface $em, AuthorizationCheckerInterface $authorizationChecker, TokenStorageInterface $tokenStorage)
    {
        $this->em                   = $em;
        $this->authorizationChecker = $authorizationChecker;
        $this->user                 = $tokenStorage->getToken()->getUser();
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
    public function changeCash(float $cash, ?UserInterface $user = null): float
    {
        $wallet = $this->em->getRepository(Wallet::class)->findOneBy(['user' => $user ?: $this->user]);
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
