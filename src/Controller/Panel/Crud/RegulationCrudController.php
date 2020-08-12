<?php

namespace ModernGame\Controller\Panel\Crud;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use ModernGame\Database\Entity\Regulation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use ModernGame\Database\Entity\RegulationCategory;
use ModernGame\Field\EntityField;

class RegulationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Regulation::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('- Zasada')
            ->setEntityLabelInPlural('Zasady');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextEditorField::new('description'),
            EntityField::new('category', 'Kategoria')
                ->setClass(RegulationCategory::class, 'name')
        ];
    }
}
