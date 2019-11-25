<?php

namespace ModernGame\Controller\Content;

use ModernGame\Database\Repository\RegulationRepository;
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
    public function getRules(RegulationRepository $repository)
    {
        return new JsonResponse($repository->findBy([], ['category' => 'ASC']));
    }
}
