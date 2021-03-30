<?php

namespace MNGame\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class ContentException extends Exception
{
    public function __construct(array $data = [])
    {
        parent::__construct(json_encode($data), Response::HTTP_BAD_REQUEST);
    }
}
