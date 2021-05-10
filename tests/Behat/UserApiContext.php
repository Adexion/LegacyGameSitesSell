<?php

namespace MNGame\Tests\Behat;

use MNGame\Database\Entity\SMSPrice;
use MNGame\Database\Entity\ResetPassword;
use MNGame\Database\Entity\User;

class UserApiContext extends AbstractContext
{
    const LOGIN_URI = 'v1/user/login';
    const REGISTER_URI = 'v1/user/register';

    private string $token;

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
     * @Given set price :price for phone number :number
     * @Given set price :price for phone number :number and amount :amount
     */
    public function setPriceForPhoneNumber(float $price, int $number, float $amount = 1)
    {
        $entity = new SMSPrice();

        $entity->setAmount($amount);
        $entity->setNumber($number);
        $entity->setPrice($price);

        $this->getManager()->getRepository(SMSPrice::class)->insert($entity);
    }

    /**
     * @Given set password reset token as :token
     */
    public function setPasswordResetToken(string $token)
    {
        /** @var User $user */
        $user = $this->getManager()->getRepository(User::class)->find(1);

        $resetPassword = new ResetPassword();

        $resetPassword->setToken($token);
        $resetPassword->setUser($user);

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
