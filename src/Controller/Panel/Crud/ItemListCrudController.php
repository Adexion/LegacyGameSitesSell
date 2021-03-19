<?php

namespace ModernGame\Controller\Panel\Crud;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AvatarField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\PercentField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use ModernGame\Database\Entity\ItemList;
use ModernGame\Database\Entity\User;
use ModernGame\Enum\RolesEnum;
use ModernGame\Field\ServerChoiceFieldProvider;
use Symfony\Component\Security\Core\Security;

class ItemListCrudController extends AbstractRoleAccessCrudController
{
    private ServerChoiceFieldProvider $fieldProvider;

    public function __construct(ServerChoiceFieldProvider $fieldProvider, Security $security)
    {
        $this->fieldProvider = $fieldProvider;
        parent::__construct($security);
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
        /** @var User $user */
        $user = $this->security->getUser();

        if (Crud::PAGE_INDEX === $pageName) {
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
                    ->setPermission(RolesEnum::ROLE_ADMIN)
                    ->setValue($user->getAssignedServerId())
            ];
        }

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
                ->setCssClass('d-none')
                ->setValue($user->getAssignedServerId())
        ];
    }
}
