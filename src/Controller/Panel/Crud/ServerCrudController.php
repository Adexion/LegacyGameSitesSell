<?php

namespace MNGame\Controller\Panel\Crud;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use MNGame\Database\Entity\Server;
use MNGame\Enum\ExecutionTypeEnum;
use ReflectionException;

class ServerCrudController extends AbstractRoleAccessCrudController
{
    public static function getEntityFqcn(): string
    {
        return Server::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Serwer')
            ->setEntityLabelInPlural('Serwery');
    }

    /**
     * @throws ReflectionException
     */
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nazwa'),
            NumberField::new('port', 'Port'),
            TextField::new('password', 'Hasło'),
            TextField::new('userOnlineCommand', 'Komenda czy user jest online'),
            ImageField::new('image', 'Grafika serwera')
                ->setUploadDir('public/assets/images')
                ->setBasePath('/assets/images'),
            ChoiceField::new('executionType', 'Typ wykonywania poleceń')
                ->setChoices(ExecutionTypeEnum::toArray()),
            TextField::new('host', 'Adres'),
            TextField::new('playerNotFoundCommunicate', 'Odp. serwera gdy nie ma gracza w grze'),
        ];
    }
}
