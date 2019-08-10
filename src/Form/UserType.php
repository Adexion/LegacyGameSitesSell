<?php

namespace ModernGame\Form;


use ModernGame\Database\Entity\User;
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
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class)
            ->add('id', HiddenType::class)
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Email(['strict' => true])
                ]
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Użytkownik' => 'ROLE_USER',
                    'Administrator' => 'ROLE_ADMIN'
                ],
                'multiple' => true
            ])
            ->add('rules', HiddenType::class)
            ->add('reCaptcha', HiddenType::class)
            ->add('ipAddress', HiddenType::class)
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
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
