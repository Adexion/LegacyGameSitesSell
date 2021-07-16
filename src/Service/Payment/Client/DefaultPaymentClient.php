<?php

namespace MNGame\Service\Payment\Client;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\TransactionRequiredException;
use MNGame\Database\Entity\Configuration;
use MNGame\Database\Entity\Item;
use MNGame\Database\Entity\ItemList;
use MNGame\Database\Entity\UserItem;
use MNGame\Database\Repository\PaymentHistoryRepository;
use MNGame\Database\Repository\SMSPriceRepository;
use MNGame\Enum\PaymentConfigurationType;
use MNGame\Enum\PaymentStatusEnum;
use MNGame\Exception\PaymentProcessingException;
use MNGame\Service\ApiClient\RestApiClient;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DefaultPaymentClient extends RestApiClient implements PaymentClientInterface
{
    protected Collection $paymentConfiguration;
    protected PaymentHistoryRepository $paymentHistoryRepository;
    protected SMSPriceRepository $smsPriceRepository;
    protected TokenStorageInterface $tokenStorage;

    public function __construct(SMSPriceRepository $smsPriceRepository, Collection $paymentConfiguration, PaymentHistoryRepository $paymentHistoryRepository, TokenStorageInterface $tokenStorage)
    {
        parent::__construct();
        $this->paymentConfiguration = $paymentConfiguration;
        $this->paymentHistoryRepository = $paymentHistoryRepository;
        $this->smsPriceRepository = $smsPriceRepository;
        $this->tokenStorage = $tokenStorage;
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