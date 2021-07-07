<?php

namespace MNGame\Service\Payment;

use ReflectionException;
use MNGame\Form\PaymentType;
use MNGame\Database\Entity\Payment;
use Symfony\Component\Form\FormView;
use MNGame\Enum\PaymentConfigurationType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormFactoryInterface;
use MNGame\Database\Repository\ParameterRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class PaymentTypeFormFactory
{
    private FormFactoryInterface $formFactory;
    private ParameterRepository $parameterRepository;

    public function __construct(FormFactoryInterface $formFactory, ParameterRepository $parameterRepository)
    {
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
        $action               = $builder->getActionUri($payment);

        return $this
            ->getForm($paymentConfiguration, $action)
            ->submit($paymentConfiguration)
            ->createView();
    }

    private function getForm(array $paymentConfiguration, string $action): FormInterface
    {
        $form = $this->formFactory->create(PaymentType::class, null, ['action' => $action]);
        foreach ($paymentConfiguration as $key => $datum) {
            if (PaymentConfigurationType::METHOD === $key) {
                continue;
            }

            $form->add($key, PaymentConfigurationType::INPUT === $key ? TextType::class : HiddenType::class);
        }

        return $form;
    }
}