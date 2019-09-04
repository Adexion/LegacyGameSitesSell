<?php

namespace ModernGame\Tests\Behat;

use ModernGame\Database\Entity\Price;
use ModernGame\Database\Entity\ResetPassword;
use ModernGame\Database\Entity\User;

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

        $this->getManager()->getRepository(Price::class)->insert($price);
    }

    /**
     * @Given set password reset token as :token
     */
    public function setPasswordResetToken(string $token)
    {
        $resetPassword = new ResetPassword();

        $resetPassword->setToken($token);
        $resetPassword->setUserId(1);

        $this->getManager()->getRepository(ResetPassword::class)->insert($resetPassword);
    }

    /**
     * @Given As logged admin user
     */
    public function loggedAdminUser()
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

        $userRepository =  $this->getManager()->getRepository(User::class);

        /** @var User $user */
        $user = $userRepository->find(1);
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);

        $userRepository->update($user);

        $this->setRequestHeader('Content-Type', 'application/json');
        $this->setRequestBody(json_encode($loginRequest));
        $response = $this->requestPath(self::LOGIN_URI,'POST');

        $this->token = ((array)$response->getResponseBody())['token'];
    }
}
