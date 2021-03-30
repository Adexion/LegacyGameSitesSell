<?php

namespace MNGame\Form;

use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Date;

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
            ->add('userId', NumberType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'allow_extra_fields' => true
        ]);

        parent::configureOptions($resolver);
    }
}
