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
            ->setAction('/api/admin/item/' . $options['action'] ?? null)
            ->add('name', TextType::class, array(
                'label' => 'Nazwa',
                'attr' => array(
                    'placeholder' => 'Nazwa'
                ),
            ))
            ->add('id', HiddenType::class, array(
                'label' => false
            ))
            ->add('command', TextType::class, array(
                'label' => 'Komenda',
                'attr' => array(
                    'placeholder' => 'Komenda'
                )
            ))
            ->add('iconUrl', TextType::class, array(
                'label' => 'Ikona',
                'attr' => array(
                    'placeholder' => 'Ikona'
                )
            ))
            ->add('equipmentId', ChoiceType::class, array(
                'choices' => $options['equipments'],
                'label' => 'Numer serwera',
                'attr' => array(
                    'placeholder' => 'Numer serwera'
                )
            ))
            ->add('button', ButtonType::class, [
                'attr' => [
                    'class' => 'btn-secondary user m-auto d-block send-btn',
                ],
                'label' => 'Zapisz',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(array('data_class' => EquipmentItem::class))
            ->setDefault('equipments', null)
            ->setRequired('equipments')
            ->setAllowedTypes('equipments', array('array'));
    }
}
