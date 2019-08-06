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
            ->setAction('/api/admin/article/' . $options['action'] ?? null)
            ->add('title', TextType::class, array(
                'label' => 'Tytuł',
                'attr' => array(
                    'placeholder' => 'Tytuł'
                ),
            ))
            ->add('id', HiddenType::class, array(
                'label' => false
            ))
            ->add('subhead', TextType::class, array(
                'label' => 'Podtytuł',
                'attr' => array(
                    'placeholder' => 'Podtytuł'
                )
            ))
            ->add('image', TextType::class, array(
                'label' => 'Obrazek',
                'attr' => array(
                    'placeholder' => 'Obrazek'
                )
            ))
            ->add('text', TextareaType::class, array(
                'label' => 'Opis',
                'attr' => array(
                    'placeholder' => 'Opis'
                )
            ))
            ->add('shortText', TextareaType::class, array(
                'label' => 'Krótki opis',
                'attr' => array(
                    'placeholder' => 'Krótki opis'
                )
            ))
            ->add('button', ButtonType::class, [
                'attr' => [
                    'class' => 'btn-secondary user m-auto d-block send-btn',
                ],
                'label' => 'Zapisz',
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
        $resolver->setDefaults(array(
            'data_class' => Article::class,
        ));
    }
}
