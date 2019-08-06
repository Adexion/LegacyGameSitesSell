<?php

namespace ModernGame\Form;

use ReCaptcha\ReCaptcha;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    const RE_CAPTCHA = '6LdCkl4UAAAAAPQJo2HBxfwkp9VIgrBZDLRKrtLy';

    const TECHNICAL_SUPPORT = 'support';
    const MARKETING = 'marketing';
    const REPORTS = 'reports';
    const OTHER = 'other';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setAction('/api/contact')
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Imie/Nick',
                ],
                'required' => true,
            ])
            ->add('email', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Email',
                ],
                'required' => true,
            ])
            ->add('type', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'Wsparcie Techniczne' => self::TECHNICAL_SUPPORT,
                    'Propozycje Marketingowe' => self::MARKETING,
                    'Zgłoszenia' => self::REPORTS,
                    'Inne' => self::OTHER,
                ]])
            ->add('subject', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Tytuł',
                ],
                'required' => true,
            ])
            ->add('message', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Twoja wiadomość',
                ],
                'required' => true,
            ])
            ->add('button', ButtonType::class, [
                'attr' => [
                    'class' => 'btn-secondary user m-auto d-block send-btn',
                ],
                'label' => 'Wyślij wiadomość',
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'preSubmit']);
    }

    public function preSubmit(FormEvent $event)
    {
        $reCaptcha = new ReCaptcha(self::RE_CAPTCHA);

        $reCaptchaCode = $event->getData()['g-recaptcha-response'] ?? $event->getData()['reCaptcha'] ?? null;

        $response = $reCaptcha->verify($reCaptchaCode);

        if (!$response->isSuccess()) {
            $event->getForm()->add('reCaptcha', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Potwierdź, że nie jesteś robotem.'])
                ]
            ]);
        }
    }
}
