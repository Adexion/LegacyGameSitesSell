<?php

namespace MNGame\Service\Payment;

use ReflectionException;
use Doctrine\ORM\ORMException;
use MNGame\Database\Entity\User;
use MNGame\Enum\PaymentTypeEnum;
use MNGame\Service\ServerProvider;
use MNGame\Enum\PaymentStatusEnum;
use MNGame\Database\Entity\ItemList;
use MNGame\Enum\PaymentCategoryEnum;
use Doctrine\ORM\OptimisticLockException;
use MNGame\Database\Entity\PaymentHistory;
use UnexpectedValueException;
use MNGame\Database\Repository\ItemListRepository;
use MNGame\Database\Repository\PaymentHistoryRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PaymentFormFactory
{
    private ServerProvider $serverProvider;
    private PaymentTypeFormFactory $paymentTypeFormFactory;
    private PaymentHistoryRepository $paymentHistoryRepository;
    private ItemListRepository $itemListRepository;
    private User $user;

    public function __construct(
        ServerProvider $serverProvider,
        PaymentTypeFormFactory $paymentTypeFormFactory,
        PaymentHistoryRepository $paymentHistoryRepository,
        ItemListRepository $itemListRepository,
        TokenStorageInterface $tokenStorage
    ) {
        $this->serverProvider           = $serverProvider;
        $this->paymentTypeFormFactory   = $paymentTypeFormFactory;
        $this->paymentHistoryRepository = $paymentHistoryRepository;
        $this->itemListRepository       = $itemListRepository;
        $user = $tokenStorage->getToken()->getUser();

        if ($user instanceof User) {
            $this->user = $user;
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ReflectionException
     */
    public function create(string $uniqId, string $itemId = null): array
    {
        $itemList = $this->itemListRepository->find($itemId);

        return $this->createFormList($uniqId . $itemId, $itemList->getPrice(), $itemList);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     * @throws ReflectionException
     */
    public function createFormList(string $uniqId, float $price, ?ItemList $itemList = null): array
    {
        $paymentHistory = new PaymentHistory();

        $paymentHistory->setPaymentId($uniqId);
        $paymentHistory->setAmount($price);
        $paymentHistory->setPaymentStatus(PaymentStatusEnum::PENDING);
        $paymentHistory->setItemList($itemList ?: null);
        $paymentHistory->setUser($this->user);

        $this->paymentHistoryRepository->insert($paymentHistory);

        return $this->generate($uniqId, $price, $itemList
            ? $itemList->getName()
            : PaymentTypeEnum::create(PaymentTypeEnum::PREPAID)->getKey()
        );
    }

    /**
     * @throws ReflectionException
     */
    private function generate(string $uniqId, float $price, string $name): array
    {
        foreach ($this->serverProvider->getSessionServer()->getPayments() ?? [] as $payment) {
            $formList[$this->getCategoryByPaymentName($payment->getType())][$payment->getName()]
                = $this->paymentTypeFormFactory->create($payment,  $price,  $name, $uniqId);
        }

        return $formList ?? [];
    }

    private function getCategoryByPaymentName(PaymentTypeEnum $type): string
    {
        switch ($type->getValue()) {
            case PaymentTypeEnum::HOTPAY:
                return PaymentCategoryEnum::CARD;
            case PaymentTypeEnum::MICRO_SMS:
            case PaymentTypeEnum::DIRECT_BILL:
                return PaymentCategoryEnum::SMS;
            case PaymentTypeEnum::PAY_SAFE_CARD:
                return PaymentCategoryEnum::OTHER;
        }

        throw new UnexpectedValueException();
    }
}