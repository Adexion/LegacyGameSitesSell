<?php

namespace ModernGame\Controller\Panel\Crud;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AvatarField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use ModernGame\Database\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use ModernGame\Database\Entity\User;
use ModernGame\Field\EntityField;
use ModernGame\Field\ServerChoiceFieldProvider;

class ArticleCrudController extends AbstractCrudController
{
    private ServerChoiceFieldProvider $fieldProvider;

    public function __construct(ServerChoiceFieldProvider $fieldProvider)
    {
        $this->fieldProvider = $fieldProvider;
    }

    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Artukuł')
            ->setEntityLabelInPlural('Artykuły');
    }


    public function configureFields(string $pageName): iterable
    {
        if (Crud::PAGE_INDEX === $pageName) {
            return [
                TextField::new('title', 'Tytuł'),
                TextField::new('subhead', 'Pod tytuł'),
                TextEditorField::new('text', 'Artykuł'),
                TextEditorField::new('shortText', 'Krótki opis'),
                EntityField::new('author', 'Autor')
                    ->setClass(User::class, 'username'),
                DateTimeField::new('createdAt', 'Data upublicznienia')
                    ->setFormat('y-MM-dd HH:mm')
                    ->setTimezone('Europe/Warsaw')
                    ->renderAsNativeWidget(false),
                $this->fieldProvider->getChoiceField('serverId', 'Server')
            ];
        }

        return [
            TextField::new('title', 'Tytuł'),
            TextField::new('subhead', 'Pod tytuł'),
            AvatarField::new('image', 'Obraz artykułu')
                ->setRequired(false),
            TextEditorField::new('text', 'Artykuł'),
            TextEditorField::new('shortText', 'Krótki opis'),
            EntityField::new('author', 'Autor')
                ->setClass(User::class, 'username')
                ->setRequired(true),
            DateTimeField::new('createdAt', 'Data upublicznienia')
                ->setFormat('y-MM-dd HH:mm')
                ->setTimezone('Europe/Warsaw')
                ->renderAsNativeWidget(false),
            $this->fieldProvider->getChoiceField('serverId', 'Serwer')
        ];
    }

}
