<?php

namespace ModernGame\Form;

use ModernGame\Database\Entity\EquipmentItem;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EquipmentItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nazwa',
                'attr' => [
                    'placeholder' => 'Nazwa'
                ],
            ])
            ->add('id', HiddenType::class, [
                'label' => false
            ])
            ->add('command', TextType::class, [
                'label' => 'Komenda',
                'attr' => [
                    'placeholder' => 'Komenda'
                ]
            ])
            ->add('iconUrl', TextType::class, [
                'label' => 'Ikona',
                'attr' => [
                    'placeholder' => 'Ikona'
                ]
            ])
            ->add('equipmentId', ChoiceType::class, [
                'choices' => $options['equipments'],
                'label' => 'Numer serwera',
                'attr' => [
                    'placeholder' => 'Numer serwera'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(['data_class' => EquipmentItem::class])
            ->setDefault('equipments', null)
            ->setRequired('equipments')
            ->setAllowedTypes('equipments', ['array']);
    }
}
