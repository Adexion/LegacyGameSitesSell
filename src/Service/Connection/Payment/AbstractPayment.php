<?php

namespace ModernGame\Service\Connection\Payment;

use ModernGame\Database\Entity\PaymentHistory;
use ModernGame\Database\Entity\User;
use ModernGame\Database\Repository\PaymentHistoryRepository;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

abstract class AbstractPayment
{
    private PaymentHistoryRepository $repository;
    private UserProviderInterface $userProvider;

    public function __construct(PaymentHistoryRepository $repository, UserProviderInterface $userProvider)
    {
        $this->repository = $repository;
        $this->userProvider = $userProvider;
    }

    protected function notePayment(float $amount, string $username, string $type, string $id) {
        $paymentHistory = new PaymentHistory();

        try {
            $user = $this->userProvider->loadUserByUsername($username);
        } catch (UsernameNotFoundException $exception) {
            $user = null;
        }

        if ($user instanceof User) {
            $paymentHistory->setUser($user);
        }

        $paymentHistory->setAmount($amount);
        $paymentHistory->setPaymentId($id);
        $paymentHistory->setPaymentType($type);

        $this->repository->insert($paymentHistory);
    }
}
