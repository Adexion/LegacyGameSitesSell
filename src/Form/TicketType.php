<?php

namespace ModernGame\Form;

use ModernGame\Database\Entity\Article;
use ModernGame\Database\Entity\Ticket;
use ModernGame\Validator\ReCaptchaValidator;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class TicketType extends AbstractType
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
            ->add('status')
            ->add('token')
            ->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'preSubmit']);
    }

    public function preSubmit(FormEvent $event)
    {
        $event->getForm()
            ->add('reCaptcha', TextType::class, $this->validator->validate($event->getData()['reCaptcha'] ?? ''));
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
