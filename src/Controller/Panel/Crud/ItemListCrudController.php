<?php

namespace ModernGame\Controller\Panel\Crud;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AvatarField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\PercentField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use ModernGame\Database\Entity\ItemList;
use ModernGame\Field\ServerChoiceFieldProvider;

class ItemListCrudController extends AbstractCrudController
{
    private ServerChoiceFieldProvider $fieldProvider;

    public function __construct(ServerChoiceFieldProvider $fieldProvider)
    {
        $this->fieldProvider = $fieldProvider;
    }

    public static function getEntityFqcn(): string
    {
        return ItemList::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Lista przedmiotów')
            ->setEntityLabelInPlural('Listy przedmiotów');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nazwa'),
            TextEditorField::new('description', 'Opis'),
            AvatarField::new('icon', 'Ikona'),
            AvatarField::new('sliderImage', 'Opraz prezentacji'),
            MoneyField::new('price', 'Cena')
                ->setCurrency('PLN')
                ->setStoredAsCents(false),
            PercentField::new('promotion', 'Promocja'),
            $this->fieldProvider->getChoiceField('serverId', 'Serwer')
        ];
    }
}
