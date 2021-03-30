<?php

namespace MNGame\Form;

use MNGame\Database\Entity\User;
use MNGame\Validator\ReCaptchaValidator;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPasswordType extends AbstractType
{
    private ReCaptchaValidator $validator;

    public function __construct(ReCaptchaValidator $validator)
    {
        $this->validator = $validator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class)
            ->add('reCaptcha', HiddenType::class)
            ->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'preSubmit']);
    }

    public function preSubmit(FormEvent $event)
    {
        $event
            ->getForm()
            ->add(
                'reCaptcha',
                HiddenType::class,
                $this->validator->validate($event->getData()['reCaptcha'] ?? '')
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'allow_extra_fields' => true,
        ]);

        parent::configureOptions($resolver);
    }
}
