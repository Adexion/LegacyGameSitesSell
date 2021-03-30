<?php

namespace MNGame\Validator;

use Exception;
use MNGame\Service\EnvironmentService;
use ReCaptcha\ReCaptcha;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use function Symfony\Component\String\b;

class ReCaptchaValidator
{
    private ContainerInterface $container;
    private EnvironmentService $env;

    public function __construct(ContainerInterface $container, EnvironmentService $env)
    {
        $this->container = $container;
        $this->env = $env;
    }

    public function validate(string $reCaptcha): array
    {
        try {
            $reCaptchaValidator = new ReCaptcha($this->container->getParameter('google')['recaptcha']);

            $response = $reCaptchaValidator->verify($reCaptcha);
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
