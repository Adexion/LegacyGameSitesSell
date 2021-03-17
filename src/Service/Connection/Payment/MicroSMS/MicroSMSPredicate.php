<?php

namespace ModernGame\Service\Connection\Payment\MicroSMS;

class MicroSMSPredicate
{
    public static function isResponseInvalid(array $response): bool
    {
        return isset($errormsg)
            || !isset($response['connect'])
            || $response['data']['status'] != 1;
    }
}
