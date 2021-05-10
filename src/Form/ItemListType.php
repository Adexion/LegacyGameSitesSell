<?php

namespace MNGame\Form;

use MNGame\Database\Entity\ItemList;
use MNGame\Database\Entity\SMSPrice;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
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
            ->add('icon', TextType::class)
            ->add('sliderImage', TextType::class)
            ->add('price', NumberType::class)
            ->add('promotion', NumberType::class)
            ->add('smsPrice', EntityType::class, [
                'class' => SMSPrice::class,
                'choice_label' => 'id'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ItemList::class
        ]);

        parent::configureOptions($resolver);
    }
}
