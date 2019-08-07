<?php

namespace ModernGame\Form;

use ModernGame\Database\Entity\Equipment;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EquipmentType extends AbstractType
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
            ->add('howManyBuyers', HiddenType::class, [
                'label' => false
            ])
            ->add('description', TextType::class, [
                'label' => 'Opis',
                'attr' => [
                    'placeholder' => 'Opis'
                ]
            ])
            ->add('iconUrl', TextType::class, [
                'label' => 'Ikona',
                'attr' => [
                    'placeholder' => 'Ikona'
                ]
            ])
            ->add('sliderImage', TextType::class, [
                'label' => 'Slider',
                'attr' => [
                    'placeholder' => 'Slider'
                ]
            ])
            ->add('price', NumberType::class, [
                'label' => 'Cena',
                'attr' => [
                    'placeholder' => 'Cena'
                ]
            ])
            ->add('promotion', NumberType::class, [
                'label' => 'Zniżka',
                'attr' => [
                    'placeholder' => 'Zniżka'
                ]
            ])
            ->add('serverId', ChoiceType::class, [
                'choices' => [
                    'Gemdust' => 1
                ],
                'label' => 'Numer serwera',
                'attr' => [
                    'placeholder' => 'Numer serwera'
                ]
            ])
            ->addEventListener(FormEvents::POST_SET_DATA, [$this, 'preSetData']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Equipment::class,
        ]);
    }
}
