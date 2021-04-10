<?php

namespace MNGame\Controller\Panel\Crud;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AvatarField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\PercentField;
use MNGame\Field\CKEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use MNGame\Database\Entity\ItemList;
use MNGame\Database\Entity\User;
use MNGame\Enum\RolesEnum;
use MNGame\Field\ServerChoiceFieldProvider;
use MNGame\Predicate\RolePredicate;
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
            ->setEntityLabelInPlural('Listy przedmiotów')
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');
    }

    public function configureFields(string $pageName): iterable
    {
        /** @var User $user */
        $user = $this->security->getUser();

        if (Crud::PAGE_INDEX === $pageName || RolePredicate::isAdminRoleGranted($this->security)) {
            return [
                TextField::new('name', 'Nazwa'),
                CKEditorField::new('description', 'Opis')->hideOnIndex(),
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
            CKEditorField::new('description', 'Opis')->hideOnIndex(),
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
