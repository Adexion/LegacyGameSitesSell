<?php

namespace MNGame\Service\Payment;

use ReflectionException;
use MNGame\Form\PaymentType;
use MNGame\Database\Entity\Payment;
use Symfony\Component\Form\FormView;
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

    /**
     * @throws ReflectionException
     */
    public function create(Payment $payment, float $price, string $name, string $uniqId): ?FormView
    {
        $parameter            = $this->parameterRepository->findOneBy(['name' => 'uri']);
        $builder              = new PaymentConfigurationFormBuilder($uniqId);
        $paymentConfiguration = $builder->build($payment, $price, $name, $parameter->getValue());

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