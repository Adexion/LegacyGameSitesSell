<?php

namespace MNGame\Controller\Panel\Crud;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use MNGame\Database\Entity\SiteParameter;

class SiteParameterCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SiteParameter::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::NEW);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('SEO')
            ->setEntityLabelInPlural('SEO');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title', 'Tytuł strony'),
            TextField::new('keywords', 'Slowa kluczowe (odzielone przecinkami)'),
            TextareaField::new('content', 'Opis strony'),
            ImageField::new('backgroundImage', 'Obraz tła')
                ->setUploadDir('public/assets/images')
                ->setBasePath('/assets/images'),
            ImageField::new('siteLogo', 'Logo na stronie')
                ->setUploadDir('public/assets/images')
                ->setBasePath('/assets/images'),
            ImageField::new('favicon', 'Favicon')
                ->setUploadDir('public/assets/images')
                ->setBasePath('/assets/images'),
        ];
    }
}
