<?php

namespace ModernGame\Service;

use ModernGame\Database\Repository\ItemListStatisticRepository;
use ModernGame\Database\Repository\PaymentHistoryRepository;
use ModernGame\Form\HistoryFilterType;
use ModernGame\Form\StatisticFilterType;
use ModernGame\Validator\FormErrorHandler;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class StatisticService
{
    private $statisticRepository;
    private $historyRepository;
    private $form;
    private $formErrorHandler;

    public function __construct(
        ItemListStatisticRepository $statisticRepository,
        PaymentHistoryRepository $historyRepository,
        FormFactoryInterface $form,
        FormErrorHandler $formErrorHandler
    ) {
        $this->statisticRepository = $statisticRepository;
        $this->historyRepository = $historyRepository;
        $this->form = $form;
        $this->formErrorHandler = $formErrorHandler;
    }

    public function findStatistic(Request $request)
    {
        return $this->getEntities($request, StatisticFilterType::class);
    }

    public function findHistory(Request $request)
    {
        return $this->getEntities($request, HistoryFilterType::class);
    }

    private function getEntities(Request $request, $class)
    {
        $data = [];

        $form = $this->form->create($class, $data, ['method' => 'get']);
        $form->handleRequest($request);
        $this->formErrorHandler->handle($form);

        return $this->statisticRepository->findBy($data);
    }
}
