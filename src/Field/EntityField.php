<?php

namespace MNGame\Field;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

final class EntityField implements FieldInterface
{
    use FieldTrait;

    public const OPTION_CLASS = 'class';
    public const OPTION_CHOICE_LABEL = 'choice_label';
    private string $filteredBy;
    private string $filteredValue;

    public static function new(string $propertyName, ?string $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setTemplatePath('@MNGame/panel/field/entity.html.twig')
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
                if (!$entity) {
                    return null;
                }

                if (isset($this->filteredBy) && call_user_func([$entity, 'get' . ucfirst($this->filteredBy)]) == $this->filteredValue){
                    return null;
                }

                return call_user_func([$entity, 'get' . ucfirst($choiceLabel)]);
            });

        return $this;
    }

    public function setFilteredBy(string $filteredBy, string $value): self
    {
        $this->filteredBy = $filteredBy;
        $this->filteredValue = $value;

        return $this;
    }
}
