<?php

namespace ModernGame\Controller\Panel\Crud;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AvatarField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use ModernGame\Database\Entity\Item;
use ModernGame\Database\Entity\User;
use ModernGame\Database\Entity\UserItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use ModernGame\Field\EntityField;

class UserItemCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return UserItem::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Zakupiony przedmiot ')
            ->setEntityLabelInPlural('Przedmioty');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nazwa przedmiotu'),
            AvatarField::new('icon', 'Ikona w EQ'),
            TextField::new('command', 'Komenda'),
            NumberField::new('quantity', 'Ilość przedmiotów'),
            EntityField::new('item', 'Kopia z')
                ->setClass(Item::class, 'name'),
            EntityField::new('user', 'Użytkownik')
                ->setClass(User::class, 'username')
        ];
    }
}
