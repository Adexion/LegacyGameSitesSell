<?php

namespace ModernGame\Validator;

use Exception;
use ModernGame\Service\EnvironmentService;
use ReCaptcha\ReCaptcha;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ReCaptchaValidator
{
    private $container;
    private $env;

    public function __construct(ContainerInterface $container, EnvironmentService $env)
    {
        $this->container = $container;
        $this->env = $env;
    }

    public function validate(string $reCaptcha)
    {
        if ($this->env->isTest()) {
            return [];
        }

        try {
            $reCaptcha = new ReCaptcha($this->container->getParameter('recaptcha'));

            $response = $reCaptcha->verify($reCaptcha);

            if ($response->isSuccess()) {
                return [];
            }
        } catch (Exception $e) {
        }

        return [
            'required' => true,
            'constraints' => [
                new NotBlank(['message' => 'Potwierdź, że nie jesteś robotem.'])
            ]
        ];
    }
}
