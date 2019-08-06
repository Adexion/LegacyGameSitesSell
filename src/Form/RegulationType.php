<?php

namespace ModernGame\Form;

use Symfony\Component\Form\Extension\Core\Type\ButtonType;
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
            ->setAction('/api/admin/regulation/' . $options['action'] ?? null)
            ->add('regulationId', HiddenType::class, array(
                'label' => false
            ))
            ->add('categoryId', ChoiceType::class, array(
                'choices' => $options['categories'],
                'label' => 'ID Kategorii',
                'attr' => array(
                    'placeholder' => 'ID Kategorii'
                )
            ))
            ->add('description', TextareaType::class, array(
                'label' => 'Opis',
                'attr' => array(
                    'placeholder' => 'Opis'
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
            ->setDefaults(array('data_class' => Regulation::class))
            ->setDefault('categories', null)
            ->setRequired('categories')
            ->setAllowedTypes('categories', array('array'));
    }
}
