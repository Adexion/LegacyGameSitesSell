<?php

namespace ModernGame\Field;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

final class EntityField implements FieldInterface
{
    use FieldTrait;

    public const OPTION_CLASS = 'class';
    public const OPTION_CHOICE_LABEL = 'choice_label';

    public static function new(string $propertyName, ?string $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setTemplatePath('@ModernGame/field/entity.html.twig')
            ->setFormType(EntityType::class)
            ->setRequired(true);
    }

    public function setClass($entityClass, string $choiceLabel): self
    {
        $this
            ->setCustomOption(self::OPTION_CHOICE_LABEL, $choiceLabel)
            ->setFormTypeOptions([
                self::OPTION_CLASS => $entityClass,
                self::OPTION_CHOICE_LABEL => $choiceLabel
            ])
            ->formatValue(function ($entity) use ($choiceLabel) {
                return call_user_func([$entity, 'get' . ucfirst($choiceLabel)]);
            });

        return $this;
    }
}
