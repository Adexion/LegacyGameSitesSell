<?php

namespace MNGame\Service\Payment;

use ReflectionException;
use MNGame\Form\PaymentType;
use Doctrine\ORM\ORMException;
use MNGame\Database\Entity\Payment;
use Symfony\Component\Form\FormView;
use MNGame\Database\Entity\ItemList;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Component\Form\FormFactoryInterface;
use MNGame\Database\Repository\ParameterRepository;

class PaymentTypeFormFactory
{
    private FormFactoryInterface $formFactory;
    private ParameterRepository $parameterRepository;

    public function __construct(
        FormFactoryInterface $formFactory,
        ParameterRepository $parameterRepository,
    ) {
        $this->formFactory         = $formFactory;
        $this->parameterRepository = $parameterRepository;
    }

    public function create(Payment $payment, ItemList $itemList, string $uniqId): ?FormView
    {
        if (!$itemList) {
            return null;
        }

        $parameter            = $this->parameterRepository->findOneBy(['name' => 'uri']);
        $builder              = new PaymentConfigurationFormBuilder($uniqId);
        $paymentConfiguration = $builder->build($payment, $itemList, $parameter->getValue());

        return $this->formFactory
            ->create(
                PaymentType::class,
                null,
                ['action' => $builder->getMethod($payment)]
            )
            ->submit($paymentConfiguration)
            ->createView();
    }
}