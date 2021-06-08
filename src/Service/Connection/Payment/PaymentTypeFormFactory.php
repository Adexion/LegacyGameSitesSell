<?php

namespace MNGame\Service\Connection\Payment;

use RuntimeException;
use ReflectionException;
use MNGame\Database\Entity\Payment;
use MNGame\Database\Entity\ItemList;
use Symfony\Component\Form\FormView;
use MNGame\Util\EnumKeyToCamelCaseConverter;
use Symfony\Component\Form\FormFactoryInterface;
use MNGame\Database\Repository\ItemListRepository;
use MNGame\Database\Repository\ParameterRepository;

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
    public function create(Payment $payment, ?ItemList $itemList): ?FormView
    {
        if (!$itemList) {
            return null;
        }

        $camelCase = EnumKeyToCamelCaseConverter::getCamelCase($payment->getType()->getKey());

        $formTypeClassName = 'MNGame\\Form\\Payment\\' . $camelCase . 'Type';
        if (!class_exists("$formTypeClassName")) {
            throw new RuntimeException('FormType ' . $formTypeClassName . ' does not exist');
        }

        $dtoMapper = 'MNGame\\Service\\Connection\\Payment\\Mapper\\' . $camelCase . 'DtoMapper';
        if (!class_exists($dtoMapper)) {
            throw new RuntimeException('DtoMapper ' . $formTypeClassName . ' does not exist');
        }

        $parameter = $this->parameterRepository->findOneBy(['name' => 'uri']);

        return $this->formFactory
            ->create($formTypeClassName, (new $dtoMapper())->map($payment, $itemList, $parameter->getValue()))
            ->createView();
    }
}