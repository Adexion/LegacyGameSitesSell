<?php

namespace ModernGame\Service;

use Doctrine\ORM\QueryBuilder;
use ModernGame\Database\Repository\AbstractRepository;
use ModernGame\Database\Repository\ItemListStatisticRepository;
use ModernGame\Database\Repository\PaymentHistoryRepository;
use ModernGame\Exception\ContentException;
use ModernGame\Form\FilterType;
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

    /**
     * @throws ContentException
     */
    public function findStatistic(Request $request)
    {
        return $this->getEntities($request, FilterType::class, $this->statisticRepository);
    }

    /**
     * @throws ContentException
     */
    public function findHistory(Request $request)
    {
        return $this->getEntities($request, FilterType::class, $this->historyRepository);
    }

    /**
     * @throws ContentException
     */
    private function getEntities(Request $request, $class, AbstractRepository $repository)
    {
        $form = $this->form->create($class, $request->query->all(), ['method' => 'get']);
        $form->handleRequest($request);
        $this->formErrorHandler->handle($form);

        return $repository->findAll();
    }

    private function setFilters(QueryBuilder $qb, array $data)
    {

    }
}
