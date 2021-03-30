<?php

namespace MNGame\Form;

use MNGame\Database\Entity\Ticket;
use MNGame\Validator\ReCaptchaValidator;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

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
            ->add('name', TextType::class)
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Email(['mode' => 'strict'])
                ]
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Wsparcie Techniczne' => self::TECHNICAL_SUPPORT,
                    'Propozycje Marketingowe' => self::MARKETING,
                    'Zgłoszenia' => self::REPORTS,
                    'Inne' => self::OTHER,
                ]
            ])
            ->add('subject', TextType::class)
            ->add('message', TextareaType::class)
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
            'data_class' => Ticket::class,
            'allow_extra_fields' => true
        ]);

        parent::configureOptions($resolver);
    }
}
