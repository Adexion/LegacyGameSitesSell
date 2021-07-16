<?php

namespace MNGame\Service\Payment\Client;

use Doctrine\Common\Collections\ArrayCollection;
use MNGame\Database\Repository\SMSPriceRepository;
use MNGame\Database\Repository\PaymentHistoryRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

interface PaymentClientInterface
{
    public function __construct(SMSPriceRepository $smsPriceRepository, ArrayCollection $arrayCollection, PaymentHistoryRepository $paymentHistoryRepository, TokenStorageInterface $tokenStorage);

    public function executeRequest(array $data): ?string;
}