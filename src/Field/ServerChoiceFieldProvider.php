<?php

namespace MNGame\Field;

use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use MNGame\Service\ServerProvider;

class ServerChoiceFieldProvider
{
    private ServerProvider $serverProvider;

    public function __construct(ServerProvider $serverProvider)
    {
        $this->serverProvider = $serverProvider;
    }

    public function getChoiceField(string $propertyName, string $label): ChoiceField
    {
        return ChoiceField::new($propertyName, $label)
            ->setChoices($this->getServerListChoices())
            ->setRequired(false);
    }

    public function getServerListChoices(): array
    {
        foreach ($this->serverProvider->getServerList() as $value) {
            $list[$value->getName()] = $value->getId();
        }

        return $list ?? [];
    }
}