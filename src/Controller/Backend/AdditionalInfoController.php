<?php

namespace ModernGame\Controller\Backend;

use ModernGame\Service\AdditionalInfoService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Swagger\Annotations as SWG;

class AdditionalInfoController
{
    /**
     * Get additional info
     *
     * @SWG\Tag(name="Info")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(type="integer", property="serviceCost")
     *     )
     * )
     */
    public function getPageAdditionalInfo(AdditionalInfoService $infoService)
    {
        return new JsonResponse($infoService->getAdditionalInfo());
    }
}
