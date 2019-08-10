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
            ->add('name', TextType::class)
            ->add('id', HiddenType::class)
            ->add('command', TextType::class)
            ->add('iconUrl', TextType::class)
            ->add('equipmentId', ChoiceType::class);
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
