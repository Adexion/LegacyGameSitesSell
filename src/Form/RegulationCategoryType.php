<?php

namespace ModernGame\Form;

use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegulationCategoryType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction('/api/admin/regulation/category/' . $options['action'] ?? null)
            ->add('id', HiddenType::class, array(
                'label' =>false
            ))
            ->add('categoryName', TextType::class, array(
                'label' => 'Nazwa kategorii',
                'attr' => array(
                    'placeholder' => 'Nazwa kategorii'
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
        $resolver->setDefaults(array(
            'data_class' => RegulationCategory::class,
        ));
    }
}
