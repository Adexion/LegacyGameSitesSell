<?php

namespace MNGame\Form;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use MNGame\Enum\PaymentConfigurationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('submit', SubmitType::class)
            ->setMethod('POST')
            ->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'preSubmit']);
    }

    public function preSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        foreach ($event->getData() as $key => $datum) {
            if (PaymentConfigurationType::METHOD === $key) {
                continue;
            }
            if (PaymentConfigurationType::INPUT === $key) {
                $form->add($key, TextType::class);
                continue;
            }

            $form->add($key, HiddenType::class);
        }
    }
}
