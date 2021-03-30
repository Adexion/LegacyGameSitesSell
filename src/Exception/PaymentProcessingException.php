<?php

namespace MNGame\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class PaymentProcessingException extends Exception
{
    public function __construct($message = "")
    {
        parent::__construct($message, Response::HTTP_PROCESSING);
    }
}
