<?php

namespace ModernGame\Controller\Admin;

use ModernGame\Service\StatisticService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class StatisticController extends AbstractController
{
    public function getItemListStatistic(Request $request, StatisticService $statistic)
    {
        return new JsonResponse($statistic->findStatistic($request));
    }

    public function getPaymentHistory(Request $request, StatisticService $statistic)
    {
        return new JsonResponse($statistic->findHistory($request));
    }
}
