<?php

namespace MNGame\Service\Payment;

use ReflectionException;
use Doctrine\ORM\ORMException;
use MNGame\Enum\PaymentTypeEnum;
use MNGame\Enum\PaymentStatusEnum;
use MNGame\Database\Entity\Configuration;
use MNGame\Enum\PaymentConfigurationType;
use Doctrine\ORM\OptimisticLockException;
use MNGame\Database\Entity\PaymentHistory;
use MNGame\Database\Repository\PaymentRepository;
use MNGame\Database\Repository\PaymentHistoryRepository;

class AcceptPaymentService
{
    private PaymentRepository $paymentRepository;
    private PaymentHistoryRepository $paymentHistoryRepository;

    public function __construct(PaymentRepository $paymentRepository, PaymentHistoryRepository $paymentHistoryRepository)
    {
        $this->paymentRepository        = $paymentRepository;
        $this->paymentHistoryRepository = $paymentHistoryRepository;
    }


    /**
     * @throws ReflectionException
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function accept(string $paymentType, array $data): PaymentHistory
    {
        $enumValue = PaymentTypeEnum::getValueByCamelCaseKey($paymentType);
        $payment   = $this->paymentRepository->findOneBy(['type' => $enumValue]);

        $result = $payment->getConfigurations()->filter(function (Configuration $configuration) {
            return $configuration->getType() === PaymentConfigurationType::GENERATE_ID;
        });

        $fieldNameInRequest = $data[!empty($result) ? $result->first()->getName() : 'paymentId'];

        $paymentHistory = $this->paymentHistoryRepository->findOneBy(['paymentId' => $fieldNameInRequest]);
        $paymentHistory->setPaymentType($enumValue);
        $paymentHistory->setPaymentStatus(PaymentStatusEnum::SUCCESS);

        $this->paymentHistoryRepository->update($paymentHistory);

        return $paymentHistory;
    }
}