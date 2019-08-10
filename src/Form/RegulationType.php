<?php

namespace ModernGame\Form;

use ModernGame\Database\Entity\Regulation;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegulationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('regulationId', HiddenType::class)
            ->add('categoryId', ChoiceType::class)
            ->add('description', TextareaType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(['data_class' => Regulation::class])
            ->setDefault('categories', null)
            ->setRequired('categories')
            ->setAllowedTypes('categories', ['array']);
    }
}
