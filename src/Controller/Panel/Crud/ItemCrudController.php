<?php

namespace MNGame\Controller\Panel\Crud;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AvatarField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use MNGame\Field\CKEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use MNGame\Database\Entity\Item;
use MNGame\Database\Entity\ItemList;
use MNGame\Database\Entity\User;
use MNGame\Enum\RolesEnum;
use MNGame\Field\EntityField;
use MNGame\Field\ServerChoiceFieldProvider;
use MNGame\Predicate\RolePredicate;
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

        if (Crud::PAGE_INDEX === $pageName || RolePredicate::isAdminRoleGranted($this->security)) {
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
