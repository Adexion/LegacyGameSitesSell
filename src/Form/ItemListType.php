<?php

namespace ModernGame\Form;

use ModernGame\Database\Entity\ItemList;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('id', HiddenType::class)
            ->add('howManyBuyers', HiddenType::class, [
                'empty_data' => 0
            ])
            ->add('description', TextType::class)
            ->add('iconUrl', TextType::class)
            ->add('sliderImage', TextType::class)
            ->add('price', NumberType::class)
            ->add('promotion', NumberType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ItemList::class,
        ]);
    }
}
