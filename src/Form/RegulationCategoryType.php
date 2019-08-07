<?php

namespace ModernGame\Form;

use ModernGame\Database\Entity\RegulationCategory;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegulationCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', HiddenType::class, [
                'label' => false
            ])
            ->add('categoryName', TextType::class, [
                'label' => 'Nazwa kategorii',
                'attr' => [
                    'placeholder' => 'Nazwa kategorii'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RegulationCategory::class,
        ]);
    }
}
