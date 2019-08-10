<?php

namespace ModernGame\Form;

use ModernGame\Validator\ReCaptchaValidator;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    const TECHNICAL_SUPPORT = 'support';
    const MARKETING = 'marketing';
    const REPORTS = 'reports';
    const OTHER = 'other';

    private $validator;

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
                    new Email(['strict' => true])
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
            ->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'preSubmit']);
    }

    public function preSubmit(FormEvent $event)
    {
        $event->getForm()
            ->add('reCaptcha', TextType::class, $this->validator->validate($event->getData()['reCaptcha'] ?? ''));
    }
}
