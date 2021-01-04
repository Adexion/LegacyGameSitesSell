<?php

namespace ModernGame\Controller\Panel\Crud;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use ModernGame\Database\Entity\FAQ;

class FAQCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return FAQ::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('FAQ')
            ->setEntityLabelInPlural('FAQ');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('question', 'Pytanie'),
            TextEditorField::new('answer', 'Odpowiedź')
        ];
    }
}
