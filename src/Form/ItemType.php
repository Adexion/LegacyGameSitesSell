<?php

namespace ModernGame\Form;

use ModernGame\Database\Entity\Item;
use ModernGame\Database\Entity\ItemList;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('id', HiddenType::class)
            ->add('command', TextType::class)
            ->add('iconUrl', TextType::class)
            ->add('itemListId', ChoiceType::class, [
                'choices' => $options['itemList']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(['data_class' => ItemList::class])
            ->setDefault('itemList', null)
            ->setRequired('itemList')
            ->setAllowedTypes('itemList', ['array']);
    }
}
