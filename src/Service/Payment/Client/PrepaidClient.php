<?php

namespace MNGame\Service\Payment\Client;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use MNGame\Database\Entity\ItemList;
use MNGame\Database\Entity\PaymentHistory;
use MNGame\Database\Entity\Wallet;
use MNGame\Enum\PaymentStatusEnum;
use MNGame\Enum\PaymentTypeEnum;
use MNGame\Exception\PaymentProcessingException;

class PrepaidClient extends DefaultPaymentClient implements PaymentClientInterface
{
    /**
     * @param array $data
     * @return string|null
     *
     * @throws PaymentProcessingException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function executeRequest(array $data): ?string
    {
        $_em = $this->paymentHistoryRepository->getEntityManager();

        $wallet = $_em->getRepository(Wallet::class)->findOneBy(['user' => $this->tokenStorage->getToken()->getUser()]);
        $itemListId = $_em->getRepository(ItemList::class)->find($data['itemListId']);

        if ($wallet->getCash() < $itemListId->getAfterPromotionPrice()) {
            throw new PaymentProcessingException();
        }

        $payment = new PaymentHistory();
        $payment->setPaymentType(PaymentTypeEnum::PREPAID);
        $payment->setPaymentId('GS' . date('YmdHi') . $data['itemListId']);
        $payment->setPaymentStatus(PaymentStatusEnum::PENDING);
        $payment->setItemList($itemListId);
        $payment->setUser($this->tokenStorage->getToken()->getUser());
        $payment->setAmount($itemListId->getAfterPromotionPrice());

        $this->paymentHistoryRepository->insert($payment);

        return $payment->getId();
    }
}
