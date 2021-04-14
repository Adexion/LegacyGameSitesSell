<?php

namespace MNGame\Controller\Panel\Crud;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use MNGame\Database\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use MNGame\Enum\RolesEnum;
use MNGame\Field\ServerChoiceFieldProvider;

class UserCrudController extends AbstractCrudController
{
    private ServerChoiceFieldProvider $fieldProvider;

    public function __construct(ServerChoiceFieldProvider $fieldProvider)
    {
        $this->fieldProvider = $fieldProvider;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Użytkownik')
            ->setEntityLabelInPlural('Użytkownicy');
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions
            ->setPermission(Action::EDIT, RolesEnum::ROLE_ADMIN)
            ->setPermission(Action::NEW, RolesEnum::ROLE_ADMIN)
            ->setPermission(Action::DELETE, RolesEnum::ROLE_ADMIN);

        return $actions;
    }

    public function configureFields(string $pageName): iterable
    {
        if (Crud::PAGE_EDIT === $pageName || Crud::PAGE_NEW === $pageName) {
            return [
                EmailField::new('email', 'E-mail'),
                TextField::new('username', 'Nick Minecraft'),
                TextField::new('password', 'Hasło (bcrypt)'),
                ChoiceField::new('roles', 'Rola')
                    ->setChoices(RolesEnum::toArray())
                    ->allowMultipleChoices(true)
                    ->setPermission(RolesEnum::ROLE_ADMIN),
                BooleanField::new('commercial', 'Zgody marketingowe'),
                $this->fieldProvider
                    ->getChoiceField('assignedServerId', 'Przypisany serwer')
            ];
        }

        return [
            EmailField::new('email', 'E-mail'),
            TextField::new('username', 'Nick Minecraft'),
            ChoiceField::new('roles', 'Role')
                ->setChoices(RolesEnum::toArray())
                ->allowMultipleChoices(true)
                ->setPermission(RolesEnum::ROLE_ADMIN),
            BooleanField::new('commercial', 'Zgody marketingowe')
                ->setPermission(RolesEnum::ROLE_ADMIN),
        ];
    }
}
