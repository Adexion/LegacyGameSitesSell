<?php

namespace ModernGame\Service;

use Doctrine\ORM\QueryBuilder;
use ModernGame\Database\Repository\AbstractRepository;
use ModernGame\Database\Repository\ItemListStatisticRepository;
use ModernGame\Database\Repository\PaymentHistoryRepository;
use ModernGame\Exception\ContentException;
use ModernGame\Form\FilterType;
use ModernGame\Serializer\CustomSerializer;
use ModernGame\Validator\FormErrorHandler;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class StatisticService
{
    private ItemListStatisticRepository $statisticRepository;
    private PaymentHistoryRepository $historyRepository;
    private FormFactoryInterface $form;
    private FormErrorHandler $formErrorHandler;
    private CustomSerializer $serializer;

    public function __construct(
        ItemListStatisticRepository $statisticRepository,
        PaymentHistoryRepository $historyRepository,
        FormFactoryInterface $form,
        FormErrorHandler $formErrorHandler,
        CustomSerializer $serializer
    ) {
        $this->statisticRepository = $statisticRepository;
        $this->historyRepository = $historyRepository;
        $this->form = $form;
        $this->formErrorHandler = $formErrorHandler;
        $this->serializer = $serializer;
    }

    /**
     * @throws ContentException
     */
    public function findStatistic(Request $request): array
    {
        return $this->getEntities($request, FilterType::class, $this->statisticRepository);
    }

    /**
     * @throws ContentException
     */
    public function findHistory(Request $request): array
    {
        return $this->getEntities($request, FilterType::class, $this->historyRepository);
    }

    /**
     * @throws ContentException
     */
    private function getEntities(Request $request, string $class, AbstractRepository $repository): array
    {
        $form = $this->form->create($class, $request->query->all(), ['method' => 'get']);
        $form->handleRequest($request);
        $this->formErrorHandler->handle($form);

        $qb = $repository
            ->createQueryBuilder('s')
            ->select('s');

        $this->setFilters($qb, $request->query->all());

        return $this->serializer->serialize($qb->getQuery()->execute())->toArray();
    }

    private function setFilters(QueryBuilder $qb, array $filter)
    {
        if (isset($filter['dataFrom'])) {
            $qb
                ->andWhere('s.date >= :date')
                ->setParameter(':date', $filter['dataFrom']);
        }

        if (isset($filter['dataTo'])) {
            $qb
                ->andWhere('s.date <= :date')
                ->setParameter(':date', $filter['dataTo']);
        }

        if (isset($filter['userId'])) {
            $qb
                ->andWhere('s.userId = :user')
                ->setParameter(':user', $filter['userId']);
        }
    }
}
