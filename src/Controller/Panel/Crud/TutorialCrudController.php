<?php

namespace MNGame\Controller\Panel\Crud;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use MNGame\Database\Entity\Tutorial;
use MNGame\Field\CKEditorField;

class TutorialCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tutorial::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Poradnik')
            ->setEntityLabelInPlural('Poradniki')
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');
    }

    public function configureFields(string $pageName): iterable
    {
        if (Crud::PAGE_INDEX === $pageName) {
            return [
                TextField::new('question', 'Tytuł'),
                TextareaField::new('text', 'Teskt')
            ];
        }

        return [
            TextField::new('question', 'Tytuł'),
            CKEditorField::new('text', 'Tekst')->hideOnIndex(),
            TextField::new('embed', 'Embed'),
        ];
    }
}
