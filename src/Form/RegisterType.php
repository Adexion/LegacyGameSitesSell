<?php

namespace MNGame\Form;

use MNGame\Database\Entity\User;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use MNGame\Validator\ReCaptchaValidator;
use Symfony\Component\Form\FormBuilderInterface;
use MNGame\Service\Minecraft\MojangPlayerService;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegisterType extends AbstractType
{
    private ReCaptchaValidator $validator;
    private MojangPlayerService $playerService;

    public function __construct(ReCaptchaValidator $validator, MojangPlayerService $playerService)
    {
        $this->validator     = $validator;
        $this->playerService = $playerService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => false,
                'attr'  => [
                    'placeholder' => 'Podaj nick minecraft*',
                ],
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Email(['mode' => 'strict']),
                ],
                'label'       => false,
                'attr'        => [
                    'placeholder' => 'Podaj adres e-mail*',
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type'           => PasswordType::class,
                'first_options'  => [
                    'label' => false,
                    'attr'  => [
                        'placeholder' => 'Podaj hasło*',
                    ],
                ],
                'second_options' => [
                    'label' => false,
                    'attr'  => [
                        'placeholder' => 'Powtórz hasło*',
                    ],
                ],
            ])
            ->add('rules', CheckboxType::class, [
                'constraints' => [
                    new EqualTo([
                        'value'   => true,
                        'message' => 'Proszę zaznaczyć wymagane zgody.',
                    ]),
                ],
                'label'       => 'Oświadczam, że zapoznałem/am się z Regulaminem i akceptuję wszystkie zawarte w nim warunki.',
            ])
            ->add('commercial', CheckboxType::class, [
                'required' => false,
                'label'    => 'Wyrażam zgodę na przetwarzanie moich danych osobowych w celach marketingowych, poprzez przesyłanie informacji handlowych za pomocą poczty elektronicznej, na podany adres e-mail.',
            ])
            ->add('referral', TextType::class, [
                'required' => false,
                'label'    => false,
                'attr'     => [
                    'placeholder' => 'Podaj nick polecającego ',
                ],
            ])
            ->add('reCaptcha', HiddenType::class)
            ->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'preSubmit']);
    }

    public function preSubmit(FormEvent $event)
    {
        $event
            ->getForm()
            ->add(
                'reCaptcha',
                HiddenType::class,
                $this->validator->validate($event->getData()['reCaptcha'] ?? '')
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => User::class,
            'allow_extra_fields' => true,
        ]);

        parent::configureOptions($resolver);
    }
}
