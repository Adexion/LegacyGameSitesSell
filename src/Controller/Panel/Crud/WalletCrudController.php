<?php

namespace MNGame\Controller\Panel\Crud;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use MNGame\Database\Entity\User;
use MNGame\Database\Entity\Wallet;
use MNGame\Field\EntityField;

class WalletCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Wallet::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Portfel ')
            ->setEntityLabelInPlural('Portfele');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            MoneyField::new('cash', 'Stan konta')
                ->setCurrency('PLN')
                ->setStoredAsCents(false),
            EntityField::new('user', 'Użytkownik')
                ->setClass(User::class, 'username'),
        ];
    }
}
