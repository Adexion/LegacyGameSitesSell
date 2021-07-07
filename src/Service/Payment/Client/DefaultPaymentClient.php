<?php

namespace MNGame\Service\Payment\Client;

use MNGame\Enum\PaymentStatusEnum;
use MNGame\Exception\ContentException;
use MNGame\Database\Entity\Configuration;
use MNGame\Enum\PaymentConfigurationType;
use Doctrine\Common\Collections\Collection;
use MNGame\Service\ApiClient\RestApiClient;
use MNGame\Exception\PaymentProcessingException;
use MNGame\Database\Repository\SMSPriceRepository;
use MNGame\Database\Repository\PaymentHistoryRepository;

class DefaultPaymentClient extends RestApiClient implements PaymentClientInterface
{
    protected Collection $paymentConfiguration;
    protected PaymentHistoryRepository $paymentHistoryRepository;
    protected SMSPriceRepository $smsPriceRepository;

    /**
     * DefaultPaymentClient constructor.
     *
     * @param  SMSPriceRepository        $smsPriceRepository
     * @param  Collection                $paymentConfiguration
     * @param  PaymentHistoryRepository  $paymentHistoryRepository
     */
    public function __construct(SMSPriceRepository $smsPriceRepository, Collection $paymentConfiguration, PaymentHistoryRepository $paymentHistoryRepository)
    {
        parent::__construct();
        $this->paymentConfiguration     = $paymentConfiguration;
        $this->paymentHistoryRepository = $paymentHistoryRepository;
        $this->smsPriceRepository       = $smsPriceRepository;
    }

    /**
     * @throws PaymentProcessingException
     */
    public function executeRequest(array $data): ?string
    {
        $result = $this->paymentConfiguration->filter(function (Configuration $configuration) {
            return $configuration->getType() === PaymentConfigurationType::GENERATE_ID;
        });

        $fieldNameInRequest = $data[!empty($result->first()) ? $result->first()->getName() : 'paymentId'];

        $paymentHistory = $this->paymentHistoryRepository->findOneBy(['paymentId' => $fieldNameInRequest]);
        if (!$paymentHistory) {
            throw new PaymentProcessingException();
        }

        if ($paymentHistory->getPaymentStatus() === PaymentStatusEnum::SUCCESS) {
            throw new PaymentProcessingException();
        }

        return $paymentHistory->getPaymentId();
    }
}