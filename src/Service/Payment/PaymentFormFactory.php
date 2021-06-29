<?php

namespace MNGame\Service\Payment;

use Doctrine\ORM\ORMException;
use MNGame\Database\Entity\User;
use MNGame\Enum\PaymentTypeEnum;
use MNGame\Service\ServerProvider;
use MNGame\Enum\PaymentStatusEnum;
use MNGame\Database\Entity\ItemList;
use MNGame\Enum\PaymentCategoryEnum;
use Doctrine\ORM\OptimisticLockException;
use MNGame\Database\Entity\PaymentHistory;
use http\Exception\UnexpectedValueException;
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
        $this->serverProvider = $serverProvider;
        $this->paymentTypeFormFactory = $paymentTypeFormFactory;
        $this->paymentHistoryRepository = $paymentHistoryRepository;
        $this->itemListRepository = $itemListRepository;
        $this->user = $tokenStorage->getToken()->getUser();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function create(string $uniqId, string $itemId): array
    {
        $itemList = $this->itemListRepository->find($itemId);

        $paymentHistory = new PaymentHistory();

        $paymentHistory->setPaymentId($uniqId);
        $paymentHistory->setAmount((float)$itemList->getPrice());
        $paymentHistory->setPaymentStatus(PaymentStatusEnum::PENDING);
        $paymentHistory->setItemList($itemList);
        $paymentHistory->setUser($this->user);

        $this->paymentHistoryRepository->insert($paymentHistory);

        return $this->generateForm($itemList, $uniqId);
    }

    private function generateForm(ItemList $itemList, string $uniqId): array
    {
        foreach ($this->serverProvider->getSessionServer()->getPayments() ?? [] as $payment) {
            $formList[$this->getCategoryByPaymentName($payment->getType())][$payment->getName()]
                = $this->paymentTypeFormFactory->create($payment, $itemList, $uniqId);
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