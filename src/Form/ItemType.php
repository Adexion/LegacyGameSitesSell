<?php

namespace MNGame\Form;

use MNGame\Database\Entity\ItemList;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('id', HiddenType::class)
            ->add('command', TextType::class)
            ->add('iconUrl', TextType::class)
            ->add('itemList', EntityType::class, [
                'class' => ItemList::class,
                'choice_label' => 'id'
            ]);
    }
}
