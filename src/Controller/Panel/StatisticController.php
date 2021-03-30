<?php

namespace MNGame\Controller\Panel;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Controller\DashboardControllerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use MNGame\Database\Entity\ItemListStatistic;
use MNGame\Database\Entity\PaymentHistory;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatisticController extends AbstractDashboardController implements DashboardControllerInterface
{
    use MainDashboardController;

    /**
     * @Route("/panel/statistic/shop", name="shopStatistic")
     */
    public function shopStatistic(): Response
    {
        $statistic = $this->getDoctrine()
            ->getRepository(ItemListStatistic::class)
            ->getStatistic();

        return $this->render('@MNGame/panel/shopStatistic.html.twig', [
            'dashboard_controller_filepath' => (new ReflectionClass(static::class))->getFileName(),
            'dashboard_controller_class' => (new ReflectionClass(static::class))->getShortName(),
            'statistics' => json_encode($statistic)
        ]);
    }

    /**
     * @Route("/panel/statistic/payment", name="paymentStatistic")
     */
    public function paymentStatistic(): Response
    {
        $statistic = $this->getDoctrine()
            ->getRepository(PaymentHistory::class)
            ->getStatistic();

        return $this->render('@MNGame/panel/paymentStatistic.html.twig', [
            'dashboard_controller_filepath' => (new ReflectionClass(static::class))->getFileName(),
            'dashboard_controller_class' => (new ReflectionClass(static::class))->getShortName(),
            'statistics' => json_encode($statistic)
        ]);
    }
}
