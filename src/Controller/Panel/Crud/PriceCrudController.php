<?php

namespace MNGame\Controller\Panel\Crud;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use MNGame\Database\Entity\SMSPrice;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PriceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SMSPrice::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Cennik SMS')
            ->setEntityLabelInPlural('Cenniki SMS');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TelephoneField::new('phoneNumber', 'Numer telefonu'),
            MoneyField::new('amount', 'Kwota doładowania')
                ->setCurrency('PLN')
                ->setStoredAsCents(false),
            MoneyField::new('price', 'Cena')
                ->setCurrency('PLN')
                ->setStoredAsCents(false),
        ];
    }
}
