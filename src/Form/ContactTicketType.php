<?php

namespace MNGame\Form;

use MNGame\Database\Entity\Ticket;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use MNGame\Validator\ReCaptchaValidator;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactTicketType extends AbstractType
{
    const TECHNICAL_SUPPORT = 'support';
    const MARKETING = 'marketing';
    const REPORTS = 'reports';
    const OTHER = 'other';

    private ReCaptchaValidator $validator;

    public function __construct(ReCaptchaValidator $validator)
    {
        $this->validator = $validator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr'  => [
                    'placeholder' => 'Wpisz swój nick z serwera*',
                ]
            ])
            ->add('email', EmailType::class, [
                'label'       => false,
                'constraints' => [
                    new NotBlank(),
                    new Email(['mode' => 'strict']),
                ],
                'attr'  => [
                    'placeholder' => 'Wpisz swój adres email*',
                ]
            ])
            ->add('type', ChoiceType::class, [
                'label'   => false,
                'choices' => [
                    'Wsparcie Techniczne'     => self::TECHNICAL_SUPPORT,
                    'Propozycje Marketingowe' => self::MARKETING,
                    'Zgłoszenia'              => self::REPORTS,
                    'Inne'                    => self::OTHER,
                ],
                'attr'  => [
                    'placeholder' => 'Wybierz sprawę w jakiej się kontaktuejsz*',
                ]
            ])
            ->add('subject', TextType::class, ['label' => false, 'attr' => ['placeholder' => 'Wpisz temat wiadomości*']])
            ->add('message', TextareaType::class, ['label' => false, 'attr' => ['placeholder' => 'Opisz swój problem*']])
            ->add('reCaptcha', HiddenType::class)
            ->add('token', HiddenType::class)
            ->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'preSubmit']);
    }

    public function preSubmit(FormEvent $event)
    {
        $event->getForm()
            ->add('reCaptcha', HiddenType::class, $this->validator->validate($event->getData()['reCaptcha'] ?? ''));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => Ticket::class,
            'allow_extra_fields' => true,
        ]);

        parent::configureOptions($resolver);
    }
}
