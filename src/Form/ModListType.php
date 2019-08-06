<?php

namespace ModernGame\Form;

use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction('/api/admin/mod/' . $options['action'] ?? null)
            ->add('modId', HiddenType::class, array(
                'label' =>false
            ))
            ->add('image', TextType::class, array(
                'label' => 'Ikona',
                'attr' => array(
                    'placeholder' => 'Ikona'
                )
            ))
            ->add('name', TextType::class, array(
                'label' => 'Nazwa',
                'attr' => array(
                    'placeholder' => 'Nazwa'
                )
            ))
            ->add('link', TextType::class, array(
                'label' => 'Link',
                'attr' => array(
                    'placeholder' => 'Link'
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
            'data_class' => ModList::class,
        ));
    }
}
