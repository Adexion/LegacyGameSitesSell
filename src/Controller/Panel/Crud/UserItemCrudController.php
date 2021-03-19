<?php

namespace ModernGame\Controller\Panel\Crud;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AvatarField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\PercentField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use ModernGame\Database\Entity\Item;
use ModernGame\Database\Entity\User;
use ModernGame\Database\Entity\UserItem;
use ModernGame\Enum\RolesEnum;
use ModernGame\Field\EntityField;
use ModernGame\Field\ServerChoiceFieldProvider;
use Symfony\Component\Security\Core\Security;

class UserItemCrudController extends AbstractRoleAccessCrudController
{
    private ServerChoiceFieldProvider $fieldProvider;

    public function __construct(ServerChoiceFieldProvider $fieldProvider, Security $security)
    {
        $this->fieldProvider = $fieldProvider;
        parent::__construct($security);
    }

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
        /** @var User $user */
        $user = $this->security->getUser();

        if (Crud::PAGE_INDEX === $pageName) {
            return [
                TextField::new('name', 'Nazwa przedmiotu'),
                AvatarField::new('icon', 'Ikona w EQ'),
                TextField::new('command', 'Komenda'),
                NumberField::new('quantity', 'Ilość przedmiotów'),
                EntityField::new('item', 'Kopia z')
                    ->setClass(Item::class, 'name'),
                EntityField::new('user', 'Użytkownik')
                    ->setClass(User::class, 'username'),
                $this->fieldProvider->getChoiceField('serverId', 'Serwer')
                    ->setPermission(RolesEnum::ROLE_ADMIN)
                    ->setValue($user->getAssignedServerId())
            ];
        }

        return [
            TextField::new('name', 'Nazwa przedmiotu'),
            AvatarField::new('icon', 'Ikona w EQ'),
            TextField::new('command', 'Komenda'),
            NumberField::new('quantity', 'Ilość przedmiotów'),
            EntityField::new('item', 'Kopia z')
                ->setClass(Item::class, 'name'),
            EntityField::new('user', 'Użytkownik')
                ->setClass(User::class, 'username'),
            $this->fieldProvider->getChoiceField('serverId', 'Serwer')
                ->setCssClass('d-none')
                ->setValue($user->getAssignedServerId())
        ];
    }
}
