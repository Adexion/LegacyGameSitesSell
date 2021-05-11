<?php

namespace MNGame\Service\Connection\Payment\Client;

use Doctrine\Common\Collections\ArrayCollection;
use MNGame\Database\Repository\SMSPriceRepository;
use MNGame\Service\EnvironmentService;

interface PaymentClientInterface
{
    public function __construct(SMSPriceRepository $smsPriceRepository, ArrayCollection $arrayCollection, EnvironmentService $env, ?string $className = null);

    public function executeRequest(array $data);
}