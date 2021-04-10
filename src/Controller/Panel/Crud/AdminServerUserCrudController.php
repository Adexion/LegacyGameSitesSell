<?php

namespace MNGame\Controller\Panel\Crud;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use MNGame\Field\CKEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use MNGame\Database\Entity\AdminServerUser;
use MNGame\Database\Entity\User;
use MNGame\Enum\RolesEnum;
use MNGame\Field\EntityField;
use MNGame\Field\ServerChoiceFieldProvider;
use MNGame\Predicate\RolePredicate;
use Symfony\Component\Security\Core\Security;

class AdminServerUserCrudController extends AbstractRoleAccessCrudController
{
    private ServerChoiceFieldProvider $fieldProvider;

    public function __construct(ServerChoiceFieldProvider $fieldProvider, Security $security)
    {
        $this->fieldProvider = $fieldProvider;
        parent::__construct($security);
    }

    public static function getEntityFqcn(): string
    {
        return AdminServerUser::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Admin na stronie')
            ->setEntityLabelInPlural('Admini na stronie')
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');
    }

    public function configureFields(string $pageName): iterable
    {
        /** @var User $user */
        $user = $this->security->getUser();
        if (Crud::PAGE_INDEX === $pageName || RolePredicate::isAdminRoleGranted($this->security)) {
            return [
                EntityField::new('user', 'Użytkownik')
                    ->setClass(User::class, 'username'),
                TextField::new('skinUrl', 'Adres url skina')
                    ->setRequired(false),
                CKEditorField::new('description', 'Opis')->hideOnIndex(),
                $this->fieldProvider->getChoiceField('serverId', 'Serwer')
                    ->setPermission(RolesEnum::ROLE_ADMIN)
                    ->setValue($user->getAssignedServerId())
            ];
        }

        return [
            EntityField::new('user', 'Użytkownik')
                ->setClass(User::class, 'username'),
            TextField::new('skinUrl', 'Adres url skina')
                ->setRequired(false),
            CKEditorField::new('description', 'Opis')->hideOnIndex(),
            $this->fieldProvider->getChoiceField('serverId', 'Serwer')
                ->setCssClass('d-none')
                ->setValue($user->getAssignedServerId())
        ];
    }

}
