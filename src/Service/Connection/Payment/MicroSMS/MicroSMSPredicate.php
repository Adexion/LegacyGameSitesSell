<?php

namespace ModernGame\Service\Connection\Payment\MicroSMS;

use stdClass;

class MicroSMSPredicate
{
    public static function isResponseInvalid(stdClass $response)
    {
        return isset($errormsg)
            || !isset($response->connect)
            || $response->data->status !== 1;
    }
}
