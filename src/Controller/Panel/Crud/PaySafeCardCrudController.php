<?php

namespace ModernGame\Controller\Panel\Crud;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use ModernGame\Database\Entity\PaySafeCard;
use ModernGame\Database\Entity\User;
use ModernGame\Enum\PaySafeCardStatusEnum;
use ModernGame\Field\EntityField;

class PaySafeCardCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PaySafeCard::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            MoneyField::new('money', 'Cena')
                ->setCurrency('PLN')
                ->setStoredAsCents(false),
            TextField::new('code', 'Kod'),
            ChoiceField::new('used', 'Czy został uzyty')
                ->setChoices([
                    'Nie sprawdzony' => PaySafeCardStatusEnum::NOT_USED,
                    'Użyty' => PaySafeCardStatusEnum::USED,
                    'Nie prawidłowy' => PaySafeCardStatusEnum::NOT_VALID
                ]),
            EntityField::new('user', 'Użytkownik')
                ->setClass(User::class, 'username')
        ];
    }
}
