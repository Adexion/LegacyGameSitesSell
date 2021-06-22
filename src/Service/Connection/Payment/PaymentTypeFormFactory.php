<?php

namespace MNGame\Service\Connection\Payment;

use ReflectionException;
use MNGame\Form\PaymentType;
use Doctrine\ORM\ORMException;
use MNGame\Database\Entity\User;
use MNGame\Database\Entity\Payment;
use Symfony\Component\Form\FormView;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Component\Form\FormFactoryInterface;
use MNGame\Database\Repository\ItemListRepository;
use MNGame\Database\Repository\ParameterRepository;
use MNGame\Database\Repository\PaymentHistoryRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PaymentTypeFormFactory
{
    private FormFactoryInterface $formFactory;
    private ParameterRepository $parameterRepository;
    private ItemListRepository $itemListRepository;
    private PaymentHistoryRepository $paymentHistoryRepository;
    private User $user;

    public function __construct(
        FormFactoryInterface $formFactory,
        ParameterRepository $parameterRepository,
        ItemListRepository $itemListRepository,
        PaymentHistoryRepository $paymentHistoryRepository,
        TokenStorageInterface $tokenStorage,
    ) {
        $this->formFactory              = $formFactory;
        $this->parameterRepository      = $parameterRepository;
        $this->itemListRepository       = $itemListRepository;
        $this->paymentHistoryRepository = $paymentHistoryRepository;

        $tmpUser = $tokenStorage->getToken()->getUser();
        if ($tmpUser instanceof User) {
            $this->user = $tmpUser;
        }
    }

    /**
     * @throws ReflectionException
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function create(Payment $payment, string $itemId, string $uniqId): ?FormView
    {
        $itemList = $this->itemListRepository->find($itemId);
        if (!$itemList) {
            return null;
        }

        $parameter            = $this->parameterRepository->findOneBy(['name' => 'uri']);
        $builder              = new PaymentConfigurationFormBuilder($this->paymentHistoryRepository, $uniqId, $this->user);
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