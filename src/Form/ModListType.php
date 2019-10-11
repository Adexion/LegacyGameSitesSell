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
            ->add('modId', HiddenType::class)
            ->add('image', TextType::class)
            ->add('name', TextType::class)
            ->add('link', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ModList::class,
            'csrf_protection' => false
        ]);
    }
}
