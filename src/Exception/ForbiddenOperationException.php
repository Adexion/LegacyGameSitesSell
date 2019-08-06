<?php

namespace ModernGame\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class ForbiddenOperationException extends Exception
{
    const EXCEPTION = 'Invalid credentials. Invalid username or password.';
    const CLASS_NAME = 'ForbiddenOperationException';

    public function __construct()
    {
        parent::__construct(self::EXCEPTION, Response::HTTP_BAD_REQUEST);
    }
}
