<?php

namespace ModernGame\Form;


use ModernGame\Database\Entity\Contact;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', HiddenType::class)
            ->add('contactId', HiddenType::class)
            ->add('email', HiddenType::class)
            ->add('type', HiddenType::class)
            ->add('subject', HiddenType::class)
            ->add('reCaptcha', HiddenType::class)
            ->add('token', HiddenType::class)
            ->add('status', HiddenType::class)
            ->add('message', TextareaType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
