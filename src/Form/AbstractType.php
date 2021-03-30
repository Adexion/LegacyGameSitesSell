<?php

namespace MNGame\Form;

use Symfony\Component\Form\AbstractType as BaseType;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractType extends BaseType
{
    public function getBlockPrefix()
    {
        return '';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false
        ]);
    }
}
