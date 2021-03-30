<?php

namespace MNGame\Service\Connection\ApiClient;

use MNGame\Exception\ContentException;

class RestApiClientMockProvider
{
    /**
     * @throws ContentException
     */
    public static function getMock(string $url, string $request): string
    {
        switch ($url . ': ' . $request) {
            case 'https://api.paypal.com/v1/oauth2/token: {"headers":{"Content-Type":"application\/x-www-form-urlencoded","Accept":"application\/json","Accept-Language":"en_US","Authorization":"Basic QWNzaHpZUFBxMFlHcHYwVHJ6NWF5c09RLWh4MGp4TnpNYmpIWnJTWVl3NnRlWGZBYVNjU2RlNFhHYVR1YzVkM1hUd0pFdERxRW96d1JiRkM6RUtwaG5uMHBUdGl5ZWdsdUFQWGZ1dWs0cFFXOWRCQjRTVkxuRjBhODVMS3BXaFN5b2dZNDliMzJnRnhZdnBDNFFkdG85dUlacllGQTB0cE8="},"body":{"grant_type":"client_credentials"}}':
                return '{"access_token": "VALID_ACCESS_TOKEN"}';
            case 'https://api.paypal.com/v1/payments/payment/VALID_PAYMENT_ID/execute: {"headers":{"Content-Type":"application\/json","Authorization":"Bearer VALID_ACCESS_TOKEN"},"body":"{\"payer_id\":\"VALID_PAYER_ID\"}"}':
                return '{"transactions": [{"amount": {"total": 10.55}}]}';
            case 'https://api.mojang.com/users/profiles/minecraft/test: null':
            case 'https://api.paypal.com/v1/payments/payment/INVALID_PAYMENT_ID/execute: {"headers":{"Content-Type":"application\/json","Authorization":"Bearer VALID_ACCESS_TOKEN"},"body":"{\"payer_id\":\"INVALID_PAYER_ID\"}"}':
                return '{}';
            case 'http://microsms.pl/api/v2/multi.php?userid=2003&serviceid=3235&code=VALID_CODE: null':
                return '{"connect": true,"data": {"status": 1,"used": null,"service": "3235","number": "71480","phone": "48123456789","reply": "Twoj kod to: l9j0l3g8. Dziekujemy za zakupy. Nie zapomnij kupic przedmiotu!!"}}';
            case 'http://microsms.pl/api/v2/multi.php?userid=2003&serviceid=3235&code=INVALID_FORMAT_VALUE: null':
                return '{"connect": false,"data": {"errorCode": 1,"message": "Code does not exist"}}';
            case 'http://microsms.pl/api/v2/multi.php?userid=2003&serviceid=3235&code=INVALID_VALUE: null':
                return '{ "connect": true,"data": {"status": 0,"used": "0","service": 0,"number": 0, "phone": 0,"reply": ""}}';
            case 'https://api.mojang.com/users/profiles/minecraft/adexion: null':
                return '{"id":"48fbb5a077394d8da623ecff6f87ad79","name":"Adexion"}';
        }

        throw new ContentException(['error' =>'Mock not found', 'mock' => $url . ': ' . $request]);
    }
}
