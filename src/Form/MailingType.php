<?php

namespace ModernGame\Form;

use ModernGame\Database\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\NotBlank;

class MailingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Tytuł', 'constraints' => [
                new NotBlank()
            ]])
            ->add('content', TextareaType::class, ['label' => 'Treść', 'constraints' => [
                new NotBlank()
            ]])
            ->add('userList', EntityType::class, [
                'multiple' => true,
                'class' => User::class,
                'choice_label' => 'username',
                'expanded' => true,
                'label' => 'Lista użytkowników',
                'attr' => ['class' => 'check'],
                'constraints' => [
                    new NotBlank(),
                    new Count(['min' => 1])
                ]
            ]);
    }
}