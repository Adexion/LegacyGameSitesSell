<?php

namespace ModernGame\Controller\Content;

use ModernGame\Service\Content\RegulationService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Swagger\Annotations as SWG;

class RegulationController
{
    /**
     * @SWG\Tag(name="Regulation")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function getRules(RegulationService $service)
    {
        return new JsonResponse($service->getRules());
    }
}
