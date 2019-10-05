<?php

namespace ModernGame\Controller\Admin;

use ModernGame\Service\Serializer;
use ModernGame\Service\StatisticService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class StatisticController extends AbstractController
{
    public function getItemListStatistic(Request $request, StatisticService $statistic, Serializer $serializer)
    {
        return new JsonResponse(
            $serializer->toArray($statistic->findStatistic($request), ['groups' => 'statistic'])
        );
    }

    public function getPaymentHistory(Request $request, StatisticService $statistic, Serializer $serializer)
    {
        return new JsonResponse(
            $serializer->toArray( $statistic->findHistory($request))
        );
    }
}
