<?php

namespace MNGame\Form;

use MNGame\Database\Entity\User;
use MNGame\Service\Connection\Minecraft\MojangPlayerService;
use MNGame\Validator\ReCaptchaValidator;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegisterType extends AbstractType
{
    private ReCaptchaValidator $validator;
    private MojangPlayerService $playerService;

    public function __construct(ReCaptchaValidator $validator, MojangPlayerService $playerService)
    {
        $this->validator = $validator;
        $this->playerService = $playerService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class)
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Email(['mode' => 'strict'])
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class
            ])
            ->add('rules', CheckboxType::class, [
                'constraints' => [
                    new EqualTo([
                        'value' => true,
                        'message' => 'Proszę zaznaczyć wymagane zgody.'
                    ])
                ],
                'label' => 'Rule text'
            ])
            ->add('commercial', CheckboxType::class, [
                'required' => false,
                'label' => 'Commercial text'
            ])
            ->add('referral', TextType::class, [
                'required' => false
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
            'data_class' => User::class,
            'allow_extra_fields' => true,
        ]);

        parent::configureOptions($resolver);
    }
}
