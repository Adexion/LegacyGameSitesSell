<?php

namespace MNGame\Service\Connection\Payment\Mapper;

use MNGame\Database\Entity\Payment;
use MNGame\Database\Entity\ItemList;
use MNGame\Database\Entity\Configuration;
use MNGame\Service\Connection\Payment\Dto\HotPayDto;

class HotPayDtoMapper
{
    public function map(Payment $payment, ItemList $itemList, string $uri): HotPayDto
    {
        $hotPayDto = new HotPayDto();

        /** @var Configuration $configuration */
        foreach ($payment->getConfigurations() as $configuration) {
            if ($configuration->getName() === 'secret') {
                $hotPayDto->SEKRET = $configuration->getValue();
                break;
            }
        }

        $hotPayDto->ADRES_WWW = $uri . 'payment/hotPay';
        $hotPayDto->KWOTA = $itemList->getPrice();
        $hotPayDto->ID_ZAMOWIENIA = uniqid();
        $hotPayDto->NAZWA_USLUGI  = $itemList->getName();

        return $hotPayDto;
    }
}