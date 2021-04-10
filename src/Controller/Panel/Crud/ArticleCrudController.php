<?php

namespace MNGame\Controller\Panel\Crud;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AvatarField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use MNGame\Field\CKEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use MNGame\Database\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use MNGame\Database\Entity\User;
use MNGame\Enum\RolesEnum;
use MNGame\Field\EntityField;
use MNGame\Field\ServerChoiceFieldProvider;
use MNGame\Predicate\RolePredicate;
use Symfony\Component\Security\Core\Security;

class ArticleCrudController extends AbstractRoleAccessCrudController
{
    private ServerChoiceFieldProvider $fieldProvider;

    public function __construct(ServerChoiceFieldProvider $fieldProvider, Security $security)
    {
        $this->fieldProvider = $fieldProvider;
        parent::__construct($security);
    }

    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Artukuł')
            ->setEntityLabelInPlural('Artykuły')
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');
    }

    public function configureFields(string $pageName): iterable
    {
        /** @var User $user */
        $user = $this->security->getUser();
        if (Crud::PAGE_INDEX === $pageName || RolePredicate::isAdminRoleGranted($this->security)) {
            return [
                TextField::new('title', 'Tytuł'),
                TextField::new('subhead', 'Pod tytuł'),
                CKEditorField::new('text', 'Artykuł')->hideOnIndex(),
                CKEditorField::new('shortText', 'Krótki opis')->hideOnIndex(),
                EntityField::new('author', 'Autor')
                    ->setClass(User::class, 'username'),
                DateTimeField::new('createdAt', 'Data upublicznienia')
                    ->setFormat('y-MM-dd HH:mm')
                    ->setTimezone('Europe/Warsaw')
                    ->renderAsNativeWidget(false),
                $this->fieldProvider->getChoiceField('serverId', 'Server')
                    ->setPermission(RolesEnum::ROLE_ADMIN)
                    ->setValue($user->getAssignedServerId())
            ];
        }

        return [
            TextField::new('title', 'Tytuł'),
            TextField::new('subhead', 'Pod tytuł'),
            AvatarField::new('image', 'Obraz artykułu')
                ->setRequired(false),
            CKEditorField::new('text', 'Artykuł')->hideOnIndex(),
            CKEditorField::new('shortText', 'Krótki opis')->hideOnIndex(),
            EntityField::new('author', 'Autor')
                ->setClass(User::class, 'username')
                ->setRequired(true),
            DateTimeField::new('createdAt', 'Data upublicznienia')
                ->setFormat('y-MM-dd HH:mm')
                ->setTimezone('Europe/Warsaw')
                ->renderAsNativeWidget(false),
            $this->fieldProvider->getChoiceField('serverId', 'Serwer')
                ->setCssClass('d-none')
                ->setValue($user->getAssignedServerId())
        ];
    }
}
