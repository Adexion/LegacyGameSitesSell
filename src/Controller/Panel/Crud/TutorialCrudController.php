<?php

namespace ModernGame\Controller\Panel\Crud;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use ModernGame\Database\Entity\Tutorial;

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
            ->setEntityLabelInPlural('Poradniki');
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
            TextEditorField::new('text', 'Tekst'),
            TextField::new('embed', 'Embed'),
        ];
    }
}
