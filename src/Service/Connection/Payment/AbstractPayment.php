<?php

namespace ModernGame\Service\Connection\Payment;

use ModernGame\Database\Entity\PaymentHistory;
use ModernGame\Database\Entity\User;
use ModernGame\Database\Repository\PaymentHistoryRepository;
use stdClass;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

abstract class AbstractPayment
{
    private $repository;

    /**
     * @var User
     */
    private $user;

    public function __construct(PaymentHistoryRepository $repository, TokenStorageInterface $tokenStorage)
    {
        $this->repository = $repository;
        $this->user = $tokenStorage->getToken()->getUser();
    }

    protected function notePayment($amount) {
        $paymentHistory = new PaymentHistory();

        if (!is_string($this->user)) {
            $userId = $this->user->getId();
        }

        $paymentHistory->setUserId($userId ?? 0);
        $paymentHistory->setAmount($amount);

        $this->repository->insert($paymentHistory);
    }
}
