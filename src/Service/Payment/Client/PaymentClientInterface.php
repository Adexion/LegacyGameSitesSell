<?php

namespace MNGame\Service\Payment\Client;

use Doctrine\Common\Collections\ArrayCollection;
use MNGame\Database\Repository\SMSPriceRepository;
use MNGame\Database\Repository\PaymentHistoryRepository;

interface PaymentClientInterface
{
    public function __construct(SMSPriceRepository $smsPriceRepository, ArrayCollection $arrayCollection, PaymentHistoryRepository $paymentHistoryRepository);

    public function executeRequest(array $data): ?string;
}