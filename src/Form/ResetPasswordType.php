<?php

namespace ModernGame\Form;

use ModernGame\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction('/api/reset-password')
            ->add('username', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nick',
                ],
                'required' => true,
            ])
            ->add('button', ButtonType::class, [
                'attr' => [
                    'class' => 'btn-secondary user m-auto d-block send-btn',
                ],
                'label' => 'Zresetuj hasło',
            ]);
    }
}
