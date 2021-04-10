<?php

namespace MNGame\Controller\Panel\Crud;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use MNGame\Field\CKEditorField;
use MNGame\Database\Entity\Regulation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use MNGame\Database\Entity\RegulationCategory;
use MNGame\Field\EntityField;

class RegulationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Regulation::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Zasada')
            ->setEntityLabelInPlural('Zasady')
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            CKEditorField::new('description')->hideOnIndex(),
            EntityField::new('category', 'Kategoria')
                ->setClass(RegulationCategory::class, 'name')
        ];
    }
}
