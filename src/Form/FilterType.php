<?php

namespace ModernGame\Form;

use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Type;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateFrom', TextType::class, [
                'constraints' => [
                    new Date()
                ]
            ])
            ->add('dateTo', TextType::class, [
                'constraints' => [
                    new Date()
                ]
            ])
            ->add('userId', NumberType::class, [
                'constraints' => [
                    new Type('integer')
                ]
            ]);
    }
}
