<?php

namespace ModernGame\Form;

use ModernGame\Database\Entity\User;
use ModernGame\Validator\ReCaptchaValidator;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegisterType extends AbstractType
{
    private $validator;

    public function __construct(ReCaptchaValidator $validator)
    {
        $this->validator = $validator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class)
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Email(['mode' => 'strict'])
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class
            ])
            ->add('rules', CheckboxType::class, [
                'constraints' => [
                    new EqualTo([
                        'value' => true,
                        'message' => 'Proszę zaznaczyć wymagane zgody.'
                    ])
                ]
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'preSubmit']);

        $builder
            ->get('email')
            ->addModelTransformer($this->filterCharacters());

    }

    public function preSubmit(FormEvent $event)
    {
        $event
            ->getForm()
            ->add(
                'reCaptcha',
                TextType::class,
                $this->validator->validate($event->getData()['reCaptcha'] ?? '')
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'allow_extra_fields' => false,
            'csrf_protection' => false
        ]);
    }

    private function filterCharacters()
    {
        return new CallbackTransformer(function () {
        }, function ($value) {
            if (!isset($value)) {
                return $value;
            }

            $value = explode('@', $value);

            return isset($value[1]) ? str_replace('.', '', $value[0]) . '@' . $value[1] : $value[0];
        });
    }
}
