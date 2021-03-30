<?php

namespace MNGame\Form;

use MNGame\Database\Entity\Article;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
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
            ->add('title', TextType::class)
            ->add('id', HiddenType::class)
            ->add('subhead', TextType::class)
            ->add('image', TextType::class)
            ->add('text', TextareaType::class)
            ->add('shortText', TextareaType::class)
            ->add('author')
            ->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'preSubmit']);
    }

    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();
        if ($data['createdAt'] ?? '') {
            unset($data['createdAt']);
            $event->setData($data);
        }

        if (!empty($data['text'])) {
            return;
        }

        $event->getForm()->remove('text');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class
        ]);

        parent::configureOptions($resolver);
    }
}
