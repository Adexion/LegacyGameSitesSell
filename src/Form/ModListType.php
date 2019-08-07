<?php

namespace ModernGame\Form;

use ModernGame\Database\Entity\ModList;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('modId', HiddenType::class, [
                'label' => false
            ])
            ->add('image', TextType::class, [
                'label' => 'Ikona',
                'attr' => [
                    'placeholder' => 'Ikona'
                ]
            ])
            ->add('name', TextType::class, [
                'label' => 'Nazwa',
                'attr' => [
                    'placeholder' => 'Nazwa'
                ]
            ])
            ->add('link', TextType::class, [
                'label' => 'Link',
                'attr' => [
                    'placeholder' => 'Link'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ModList::class,
        ]);
    }
}
