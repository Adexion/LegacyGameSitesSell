<?php

namespace ModernGame\Controller\Panel\Crud;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use ModernGame\Database\Entity\AdminServerUser;
use ModernGame\Database\Entity\User;
use ModernGame\Field\EntityField;
use ModernGame\Field\ServerChoiceFieldProvider;

class AdminServerUserCrudController extends AbstractCrudController
{
    private ServerChoiceFieldProvider $fieldProvider;

    public function __construct(ServerChoiceFieldProvider $fieldProvider)
    {
        $this->fieldProvider = $fieldProvider;
    }

    public static function getEntityFqcn(): string
    {
        return AdminServerUser::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Admin na stronie')
            ->setEntityLabelInPlural('Admini na stronie');
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            EntityField::new('user', 'Użytkownik')
                ->setClass(User::class, 'username'),
            TextField::new('skinUrl', 'Adres url skina')
                ->setRequired(false),
            TextEditorField::new('description', 'Opis'),
            $this->fieldProvider->getChoiceField('serverId', 'Serwer')
        ];
    }

}
