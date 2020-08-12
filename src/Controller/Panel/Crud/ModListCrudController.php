<?php

namespace ModernGame\Controller\Panel\Crud;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AvatarField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use ModernGame\Database\Entity\ModList;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ModListCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ModList::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('- Modyfikacja')
            ->setEntityLabelInPlural('Modyfikacje');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AvatarField::new('image' ,'Obraz'),
            TextField::new('name', 'Nazwa'),
            UrlField::new('link', 'Link do modyfikacji'),
        ];
    }
}
