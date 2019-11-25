<?php

namespace ModernGame\Controller\Content;

use ModernGame\Database\Repository\RegulationRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class RegulationController
{
    public function getRules(RegulationRepository $repository)
    {
        return new JsonResponse($repository->findBy([], ['category' => 'ASC']));
    }
}
