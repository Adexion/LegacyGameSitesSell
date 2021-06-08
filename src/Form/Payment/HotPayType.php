<?php

namespace MNGame\Form\Payment;

use MNGame\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class HotPayType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction('https://platnosc.hotpay.pl/')
            ->setMethod('POST')
            ->add('ID_ZAMOWIENIA', HiddenType::class)
            ->add('NAZWA_USLUGI', HiddenType::class)
            ->add('KWOTA', HiddenType::class)
            ->add('SEKRET', HiddenType::class)
            ->add('ADRES_WWW', HiddenType::class)
            ->add('hotPayButton', SubmitType::class);
    }
}
