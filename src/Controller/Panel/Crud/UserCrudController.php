<?php

namespace ModernGame\Controller\Panel\Crud;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use ModernGame\Database\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('- Użytkownik')
            ->setEntityLabelInPlural('Użytkownicy');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            EmailField::new('email', 'E-mail'),
            TextField::new('username', 'Nick Minecraft'),
            TextField::new('password', 'Hasło (bcrypt)'),
            CollectionField::new('roles', 'Role'),
            BooleanField::new('rules', 'Regulamin')
        ];
    }
}
