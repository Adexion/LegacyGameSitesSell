<?php

namespace ModernGame\Form;

use Symfony\Component\Form\AbstractType as BaseType;

abstract class AbstractType extends BaseType
{
    public function getBlockPrefix()
    {
        return null;
    }
}
