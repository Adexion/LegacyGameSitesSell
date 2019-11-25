<?php

namespace ModernGame\Controller\Admin;

use ModernGame\Service\StatisticService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Swagger\Annotations as SWG;

class StatisticController extends AbstractController
{
    /**
     * @SWG\Tag(name="Admin/Statistic")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function getItemListStatistic(Request $request, StatisticService $statistic)
    {
        return new JsonResponse($statistic->findStatistic($request));
    }

    /**
     * @SWG\Tag(name="Admin/Statistic")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function getPaymentHistory(Request $request, StatisticService $statistic)
    {
        return new JsonResponse($statistic->findHistory($request));
    }
}
