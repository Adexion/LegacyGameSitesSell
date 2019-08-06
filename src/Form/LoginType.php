<?php

namespace ModernGame\Form;

use ModernGame\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction('/api/login')
            ->add('_username', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nick',
                ],
                'required' => true,
            ])
            ->add('_password', PasswordType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Hasło',
                ],
                'required' => true,
            ])
            ->add('button', ButtonType::class, [
                'attr' => [
                    'class' => 'btn-secondary user m-auto d-block send-btn',
                ],
                'label' => 'Zaloguj się',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
