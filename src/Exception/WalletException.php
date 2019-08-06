<?php

namespace ModernGame\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class WalletException extends Exception
{
    const WALLET_EXCEPTION = 'Nie posiadasz wystarczająco dużo środków na koncie.';

    public function __construct()
    {
        parent::__construct(self::WALLET_EXCEPTION, Response::HTTP_BAD_REQUEST);
    }
}
