<?php

namespace MNGame\Controller\Panel\Crud;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use MNGame\Database\Entity\PaySafeCard;
use MNGame\Database\Entity\User;
use MNGame\Enum\PaySafeCardStatusEnum;
use MNGame\Field\EntityField;

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
