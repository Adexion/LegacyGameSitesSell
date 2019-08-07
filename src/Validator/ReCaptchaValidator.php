<?php

namespace ModernGame\Validator;

use ReCaptcha\ReCaptcha;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ReCaptchaValidator
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function validate($reCaptcha)
    {
        $reCaptcha = new ReCaptcha($this->container);

        $reCaptchaCode = ['reCaptcha'] ?? null;

        $response = $reCaptcha->verify($reCaptcha);

        if ($response->isSuccess()) {
            return [];
        }

        return [
            'required' => true,
            'constraints' => [
                new NotBlank(['message' => 'Potwierdź, że nie jesteś robotem.'])
            ]
        ];
    }
}
