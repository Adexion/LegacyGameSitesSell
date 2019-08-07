<?php

namespace ModernGame\Form;

use ModernGame\Database\Entity\Regulation;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegulationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('regulationId', HiddenType::class, [
                'label' => false
            ])
            ->add('categoryId', ChoiceType::class, [
                'choices' => $options['categories'],
                'label' => 'ID Kategorii',
                'attr' => [
                    'placeholder' => 'ID Kategorii'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Opis',
                'attr' => [
                    'placeholder' => 'Opis'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(['data_class' => Regulation::class])
            ->setDefault('categories', null)
            ->setRequired('categories')
            ->setAllowedTypes('categories', ['array']);
    }
}
