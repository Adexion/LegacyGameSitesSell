<?php

namespace ModernGame\Form;

use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegisterType extends AbstractType
{
    const RE_CAPTCHA = '6LdCkl4UAAAAAPQJo2HBxfwkp9VIgrBZDLRKrtLy';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction('/api/register')
            ->add('username', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nick',
                ],
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'E-Mail',
                ],
                'required' => true,
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Hasło',
                    ],
                ],
                'second_options' => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Powtórz hasło',
                    ],
                ],
                'required' => true,
            ])
            ->add('rules', CheckboxType::class, [
                'label' => 'Wyrażam zgodę na przetwarzanie moich danych osobowych zawartych w formularzu w celu rejestracji w
                    systemie, aby móc korzystać z usług serwerów będących wpółpartnerami na mocy słownych umów zawartych między
                    administracją systemu, a administracją serwerów. Akceptuję także regulamin serwisu, oraz zobowiązuję się do
                    przestrzegania go.',
                'required' => true,
                'attr' => [],
                'constraints' => [
                    new EqualTo([
                        'value' => true,
                        'message' => 'Proszę zaznaczyć wymagane zgody.'
                    ])
                ]
            ])
            ->add('button', ButtonType::class, [
                'attr' => [
                    'class' => 'btn-secondary user m-auto d-block send-btn',
                ],
                'label' => 'Zarejestruj się',
            ])->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'preSubmit']);

        $builder
            ->get('email')
            ->addModelTransformer($this->filterCharacters());

    }

    public function preSubmit(FormEvent $event)
    {
        $reCaptcha = new ReCaptcha(self::RE_CAPTCHA);

        $reCaptchaCode = $event->getData()['g-recaptcha-response'] ?? $event->getData()['reCaptcha'] ?? null;

        $response = $reCaptcha->verify($reCaptchaCode);

        if (!$response->isSuccess()) {
            $event->getForm()->add('reCaptcha', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Potwierdź, że nie jesteś robotem.'])
                ]
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'allow_extra_fields' => true
        ]);
    }

    private function filterCharacters()
    {
        return new CallbackTransformer(function () {
        }, function ($value) {
            if (!isset($value)) {
                return $value;
            }

            $value = explode('@', $value);
            return str_replace('.', '', $value[0]) . '@' . $value[1];
        });
    }
}
