<?php

namespace MNGame\Controller\Panel\Crud;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use MNGame\Field\CKEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use MNGame\Database\Entity\FAQ;

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
            ->setEntityLabelInPlural('FAQ')
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('question', 'Pytanie'),
            CKEditorField::new('answer', 'Odpowiedź')->hideOnIndex()
        ];
    }
}
