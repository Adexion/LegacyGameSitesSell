<?php

namespace MNGame\Service\Connection\Payment;

use Doctrine\ORM\ORMException;
use MNGame\Database\Entity\PaymentHistory;
use MNGame\Database\Entity\User;
use MNGame\Database\Repository\PaymentHistoryRepository;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

abstract class AbstractPayment
{
    private PaymentHistoryRepository $repository;
    private UserProviderInterface $userProvider;
    protected PaymentClientFactory $clientFactory;

    public function __construct(
        PaymentHistoryRepository $repository,
        UserProviderInterface $userProvider,
        PaymentClientFactory $clientFactory
    ) {
        $this->repository = $repository;
        $this->userProvider = $userProvider;
        $this->clientFactory = $clientFactory;
    }

    /**
     * @throws ORMException
     */
    protected function notePayment(float $amount, string $username, string $type, string $id, string $status)
    {
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
        $paymentHistory->setPaymentStatus($status);

        $this->repository->insert($paymentHistory);
    }
}
