<?php

namespace ModernGame\Form;

use ModernGame\Database\Entity\Regulation;
use ModernGame\Database\Entity\RegulationCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            ->add('category', EntityType::class, [
                'class' => RegulationCategory::class,
                'choice_label' => 'id'
            ])
            ->add('description', TextareaType::class);
    }
}
