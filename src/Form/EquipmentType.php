<?php

namespace ModernGame\Form;

use ModernGame\Database\Entity\Equipment;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EquipmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction('/api/admin/equipment/' . $options['action'] ?? null)
            ->add('name', TextType::class, array(
                'label' => 'Nazwa',
                'attr' => array(
                    'placeholder' => 'Nazwa'
                ),
            ))
            ->add('id', HiddenType::class, array(
                'label' => false
            ))
            ->add('howManyBuyers', HiddenType::class, array(
                'label' => false
            ))
            ->add('description', TextType::class, array(
                'label' => 'Opis',
                'attr' => array(
                    'placeholder' => 'Opis'
                )
            ))
            ->add('iconUrl', TextType::class, array(
                'label' => 'Ikona',
                'attr' => array(
                    'placeholder' => 'Ikona'
                )
            ))
            ->add('sliderImage', TextType::class, array(
                'label' => 'Slider',
                'attr' => array(
                    'placeholder' => 'Slider'
                )
            ))
            ->add('price', NumberType::class, array(
                'label' => 'Cena',
                'attr' => array(
                    'placeholder' => 'Cena'
                )
            ))
            ->add('promotion', NumberType::class, array(
                'label' => 'Zniżka',
                'attr' => array(
                    'placeholder' => 'Zniżka'
                )
            ))
            ->add('serverId', ChoiceType::class, array(
                'choices' => [
                    'Gemdust' => 1
                ],
                'label' => 'Numer serwera',
                'attr' => array(
                    'placeholder' => 'Numer serwera'
                )
            ))
            ->add('button', ButtonType::class, [
                'attr' => [
                    'class' => 'btn-secondary user m-auto d-block send-btn',
                ],
                'label' => 'Zapisz',
            ])
            ->addEventListener(FormEvents::POST_SET_DATA, [$this, 'preSetData']);
    }

    public function preSetData(FormEvent $event)
    {
        if (empty($event->getData())) {
            $event->getForm()
                ->add('howManyBuyers', HiddenType::class, array(
                    'label' => false,
                    'data' => 0
                ));
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Equipment::class,
        ));
    }
}
