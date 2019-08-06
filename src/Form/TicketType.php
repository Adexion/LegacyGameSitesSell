<?php

namespace ModernGame\Form;


use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setAction('/api/admin/ticket/response')
            ->add('name', HiddenType::class)
            ->add('contactId', HiddenType::class)
            ->add('email', HiddenType::class)
            ->add('type', HiddenType::class)
            ->add('subject', HiddenType::class)
            ->add('reCaptcha', HiddenType::class)
            ->add('token', HiddenType::class)
            ->add('status', HiddenType::class)
            ->add('message', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Twoja odpowiedź',
                ],
                'required' => true,
            ])
            ->add('button', ButtonType::class, [
                'attr' => [
                    'class' => 'btn-secondary user m-auto d-block send-btn',
                ],
                'label' => 'Wyślij wiadomość',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Contact::class,
        ));
    }
}
