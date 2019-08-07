<?php

namespace ModernGame\Form;

use ModernGame\Database\Entity\Article;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Tytuł',
                'attr' => [
                    'placeholder' => 'Tytuł'
                ],
            ])
            ->add('id', HiddenType::class, [
                'label' => false
            ])
            ->add('subhead', TextType::class, [
                'label' => 'Podtytuł',
                'attr' => [
                    'placeholder' => 'Podtytuł'
                ]
            ])
            ->add('image', TextType::class, [
                'label' => 'Obrazek',
                'attr' => [
                    'placeholder' => 'Obrazek'
                ]
            ])
            ->add('text', TextareaType::class, [
                'label' => 'Opis',
                'attr' => [
                    'placeholder' => 'Opis'
                ]
            ])
            ->add('shortText', TextareaType::class, [
                'label' => 'Krótki opis',
                'attr' => [
                    'placeholder' => 'Krótki opis'
                ]
            ])
            ->addEventListener(FormEvents::SUBMIT, [$this, 'submit']);
    }

    public function submit(FormEvent $event)
    {
        if (!empty($event->getData()->getText())) {
            return;
        }

        $event->getForm()->remove('text');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
