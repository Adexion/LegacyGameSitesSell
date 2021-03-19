<?php

namespace ModernGame\Controller\Panel\Crud;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AvatarField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use ModernGame\Database\Entity\Item;
use ModernGame\Database\Entity\ItemList;
use ModernGame\Database\Entity\User;
use ModernGame\Enum\RolesEnum;
use ModernGame\Field\EntityField;
use ModernGame\Field\ServerChoiceFieldProvider;
use ModernGame\Predicate\RolePredicate;
use Symfony\Component\Security\Core\Security;

class ItemCrudController extends AbstractRoleAccessCrudController
{
    private ServerChoiceFieldProvider $fieldProvider;

    public function __construct(ServerChoiceFieldProvider $fieldProvider, Security $security)
    {
        $this->fieldProvider = $fieldProvider;
        parent::__construct($security);
    }

    public static function getEntityFqcn(): string
    {
        return Item::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Przedmiot')
            ->setEntityLabelInPlural('Przedmioty');
    }

    public function configureFields(string $pageName): iterable
    {
        /** @var User $user */
        $user = $this->security->getUser();

        $entityField = EntityField::new('itemList', 'Lista');
        if (RolePredicate::isOnlyServerRoleGranted($this->security)) {
            /** @var User $user */
            $user = $this->security->getUser();
            $entityField = $entityField->setFilteredBy('serverId', $user->getAssignedServerId() ?: 0);
        }

        if (Crud::PAGE_INDEX === $pageName) {
            return [
                TextField::new('name', 'Nazwa'),
                AvatarField::new('icon', 'Ikona'),
                TextField::new('command', 'Komenda'),
                $entityField->setClass(ItemList::class, 'name'),
                $this->fieldProvider->getChoiceField('serverId', 'Serwer')
                    ->setPermission(RolesEnum::ROLE_ADMIN)
                    ->setValue($user->getAssignedServerId()),
            ];
        }

        return [
            TextField::new('name', 'Nazwa'),
            AvatarField::new('icon', 'Ikona'),
            TextField::new('command', 'Komenda'),
            $entityField->setClass(ItemList::class, 'name'),
            $this->fieldProvider->getChoiceField('serverId', 'Serwer')
                ->setCssClass('d-none')
                ->setValue($user->getAssignedServerId()),
        ];
    }
}
