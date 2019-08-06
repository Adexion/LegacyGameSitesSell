<?php

namespace ModernGame\Form;


use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction('/api/admin/user/edit')
            ->add('username', TextType::class, array(
                'label' => 'Nick',
                'attr' => array(
                    'placeholder' => 'Nick'
                ),
            ))
            ->add('id', HiddenType::class, array(
                'label' => false
            ))
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
                ]
            ])
            ->add('email', EmailType::class, array(
                'label' => 'E-mail',
                'attr' => array(
                    'placeholder' => 'E-mail'
                )
            ))
            ->add('roles', ChoiceType::class, array(
                'choices' => [
                    'Użytkownik' => 'ROLE_USER',
                    'Administrator' => 'ROLE_ADMIN'
                ],
                'multiple' => true,
                'label' => 'Role'
            ))
            ->add('rules', HiddenType::class)
            ->add('reCaptcha', HiddenType::class)
            ->add('ipAddress', HiddenType::class)
            ->add('button', ButtonType::class, [
                'attr' => [
                    'class' => 'btn-secondary user m-auto d-block send-btn',
                ],
                'label' => 'Zapisz',
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'preSubmit']);
    }

    public function preSubmit(FormEvent $event)
    {
        if (empty($event->getData()['password']['first']) && empty($event->getData()['password']['second'])) {
            $event->getForm()->remove('password');
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}
