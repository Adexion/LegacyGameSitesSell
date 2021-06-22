<?php

namespace MNGame\Service\Connection\Payment;

use ReflectionException;
use Doctrine\ORM\ORMException;
use MNGame\Database\Entity\User;
use MNGame\Enum\PaymentStatusEnum;
use MNGame\Database\Entity\Payment;
use MNGame\Database\Entity\ItemList;
use MNGame\Database\Entity\Configuration;
use MNGame\Enum\PaymentConfigurationType;
use Doctrine\ORM\OptimisticLockException;
use MNGame\Database\Entity\PaymentHistory;
use Symfony\Component\Security\Core\User\UserInterface;
use MNGame\Database\Repository\PaymentHistoryRepository;

class PaymentConfigurationFormBuilder
{
    private PaymentHistoryRepository $repository;
    private User $user;
    private string $uniqId;

    public function __construct(PaymentHistoryRepository $repository, string $uniqId, User $user)
    {
        $this->repository = $repository;
        $this->uniqId = $uniqId;
        $this->user = $user;
    }

    /**
     * @throws OptimisticLockException
     * @throws ReflectionException
     * @throws ORMException
     */
    public function build(Payment $payment, ItemList $itemList, string $uri): array
    {
        /** @var Configuration $configuration */
        foreach ($payment->getConfigurations() as $configuration) {
            switch ($configuration->getType()) {
                case PaymentConfigurationType::GENERATE_ID:
                    $arr[$configuration->getName()] = $this->uniqId;

                    $paymentHistory = new PaymentHistory();

                    $paymentHistory->setPaymentId($this->uniqId);
                    $paymentHistory->setPaymentType($payment->getType()->getValue());
                    $paymentHistory->setAmount((float)$itemList->getPrice());
                    $paymentHistory->setPaymentStatus(PaymentStatusEnum::PENDING);
                    $paymentHistory->setItemListId($itemList);
                    $paymentHistory->setUser($this->user);

                    $this->repository->insert($paymentHistory);
                    break;
                case PaymentConfigurationType::STRING:
                case PaymentConfigurationType::INPUT:
                    $arr[$configuration->getName()] = $configuration->getValue();
                    break;
                case PaymentConfigurationType::URI:
                    $arr[$configuration->getName()] = $uri . $configuration->getValue();
                    break;
                case PaymentConfigurationType::PRICE:
                    $arr[$configuration->getName()] = (float)$itemList->getPrice();
                    break;
                case PaymentConfigurationType::NAME:
                    $arr[$configuration->getName()] = (float)$itemList->getName();
                    break;
            }
        }

        return $arr ?? [];
    }

    public function getMethod(Payment $payment)
    {
        return array_filter($payment->getConfigurations()->toArray(), function (Configuration $conf) {
            return $conf->getType() === PaymentConfigurationType::METHOD;
        })[0]->getValue();
    }
}