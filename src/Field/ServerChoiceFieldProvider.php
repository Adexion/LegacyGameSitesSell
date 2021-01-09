<?php

namespace ModernGame\Field;

use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use ModernGame\Service\ServerProvider;

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
            ->setRequired(true);
    }

    public function getServerListChoices(): array
    {
        foreach ($this->serverProvider->getServerList() as $key => $value) {
            $list[$value['name']] = $key;
        }

        return $list ?? [];
    }
}