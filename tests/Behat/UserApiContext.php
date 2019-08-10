<?php

namespace ModernGame\Tests\Behat;

use ModernGame\Database\Entity\Price;

class UserApiContext extends AbstractContext
{
    const LOGIN_URI = 'v1/user/login';
    const REGISTER_URI = 'v1/user/register';

    private $token;

    /**
     * @Given A register user
     */
    public function registerUser()
    {
        $registerRequest = [
            'username' => 'test',
            'email' => 'test@testowy.pl',
            'rules' => true,
            'password' => [
                'first' => 'password1234',
                'second' => 'password1234'
            ]
        ];

        $this->setRequestHeader('Content-Type', 'application/json');
        $this->setRequestBody(json_encode($registerRequest));
        $this->requestPath(self::REGISTER_URI,'POST');
    }

    /**
     * @Given As logged user
     */
    public function asLoggedUser()
    {
        $registerRequest = [
            'username' => 'test',
            'email' => 'test@testowy.pl',
            'rules' => true,
            'password' => [
                'first' => 'password1234',
                'second' => 'password1234'
            ]
        ];

        $loginRequest = [
            'username' => 'test',
            'password' => 'password1234'
        ];

        $this->setRequestHeader('Content-Type', 'application/json');
        $this->setRequestBody(json_encode($registerRequest));
        $this->requestPath(self::REGISTER_URI,'POST');

        $this->setRequestHeader('Content-Type', 'application/json');
        $this->setRequestBody(json_encode($loginRequest));
        $response = $this->requestPath(self::LOGIN_URI,'POST');

        $this->token = ((array)$response->getResponseBody())['token'];
    }

    /**
     * @Given I store token to request
     */
    public function storeTokenToRequest(){
        $this->setRequestHeader('X-AUTH-TOKEN', $this->token);
    }

    /**
     * @Given set pirce :price for phone number :number
     */
    public function setPriceForPhoneNumber(float $amount, int $number)
    {
        $price = new Price();

        $price->setAmount($amount);
        $price->setPhoneNumber($number);

        $this->getmanager()->getRepository(Price::class)->insert($price);
    }
}
