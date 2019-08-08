<?php

namespace ModernGame\Tests\Behat;

class UserApiContext extends AbstractContext
{
    const LOGIN_URI = 'v1/user/login';
    const REGISTER_URI = 'v1/user/register';

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
        $this->requestPath(self::LOGIN_URI,'POST');
    }
}
