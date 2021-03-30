<?php

namespace MNGame\Service;

use Symfony\Component\HttpFoundation\Request;

interface ServiceInterface
{
    public function mapEntity(Request $request);

    public function mapEntityById(Request $request);
}
