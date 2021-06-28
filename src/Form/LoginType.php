<?php

namespace MNGame\Form;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_username', TextType::class, [
                'label' => false,
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3, 'max' => 16])
                ],
                'attr' => [
                    'placeholder' => 'Twój nick*'
                ]
            ])
            ->add('_password', PasswordType::class, [
                'label' => false,
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 6])
                ],
                'attr' => [
                    'placeholder' => 'Hasło*'
                ]
            ]);
    }
}
