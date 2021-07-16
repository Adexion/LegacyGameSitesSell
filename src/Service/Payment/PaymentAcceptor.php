<?php

namespace MNGame\Service\Payment;

use ReflectionException;
use Doctrine\ORM\ORMException;
use MNGame\Enum\PaymentStatusEnum;
use MNGame\Service\User\WalletService;
use MNGame\Exception\ContentException;
use MNGame\Database\Entity\Configuration;
use MNGame\Enum\PaymentConfigurationType;
use MNGame\Database\Entity\PaymentHistory;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\Request;
use MNGame\Exception\PaymentProcessingException;
use MNGame\Database\Repository\PaymentRepository;
use MNGame\Service\Payment\Client\PaymentClientFactory;
use MNGame\Database\Repository\PaymentHistoryRepository;

class PaymentAcceptor
{
    private PaymentHistoryRepository $paymentHistoryRepository;
    private PaymentClientFactory $clientFactory;
    private PaymentRepository $paymentRepository;
    private WalletService $walletService;

    public function __construct(PaymentHistoryRepository $paymentHistoryRepository, PaymentClientFactory $clientFactory, PaymentRepository $paymentRepository, WalletService $walletService)
    {
        $this->paymentHistoryRepository = $paymentHistoryRepository;
        $this->clientFactory            = $clientFactory;
        $this->paymentRepository        = $paymentRepository;
        $this->walletService            = $walletService;
    }

    /**
     * @throws PaymentProcessingException
     * @throws ReflectionException
     * @throws ORMException
     * @throws ContentException
     */
    public function accept(Request $request, string $paymentType): PaymentHistory
    {
        if ($this->isPaymentAccepted($request)) {
            throw new PaymentProcessingException();
        }

        $payment   = $this->paymentRepository->findOneBy(['type' => $paymentType]);
        $paymentId = $this->clientFactory
            ->create($payment)
            ->executeRequest($request->request->all());

        $paymentHistory = $this->paymentHistoryRepository->find($paymentId);
        $this->checkAmount($payment->getConfigurations(), $paymentHistory, $request->request->all());

        $paymentHistory->setPaymentStatus(PaymentStatusEnum::SUCCESS);
        $paymentHistory->setPaymentType($paymentType);

        $this->paymentHistoryRepository->update($paymentHistory);

        return $paymentHistory;
    }

    private function isPaymentAccepted(Request $request): bool
    {
        return $request->request->get('STATUS') !== PaymentStatusEnum::SUCCESS
               && $request->request->get('status', PaymentStatusEnum::SUCCESS) !== PaymentStatusEnum::SUCCESS;
    }

    /**
     * @throws PaymentProcessingException
     * @throws ContentException
     */
    private function checkAmount(Collection $configurations, PaymentHistory $paymentHistory, array $data)
    {
        $result = $configurations->filter(function (Configuration $configuration) {
            return $configuration->getType() === PaymentConfigurationType::PRICE;
        });

        if (!$result->toArray()) {
            return;
        }

        if ($paymentHistory->getAmount() > $data[$result->first()->getName ?? 'amount']) {
            $this->walletService->changeCash($data[$result->first()->getName ?? 'amount']);

            throw new PaymentProcessingException();
        }
    }
}