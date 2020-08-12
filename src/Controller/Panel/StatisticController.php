<?php

namespace ModernGame\Controller\Panel;

use ModernGame\Database\Entity\ItemListStatistic;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatisticController extends AbstractDashboardController
{
    /**
     * @Route("/panel/statistic/shop", name="shopStatistic")
     */
    public function shopStatistic(): Response
    {
        $itemList = $this->getDoctrine()->getRepository(ItemListStatistic::class)
            ->getStatistic();

        return $this->render('panel/shopStatistic.html.twig', [
            'dashboard_controller_filepath' => (new ReflectionClass(static::class))->getFileName(),
            'dashboard_controller_class' => (new ReflectionClass(static::class))->getShortName(),
            'statistics' => json_encode($itemList)
        ]);
    }
}
