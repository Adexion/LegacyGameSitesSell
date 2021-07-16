<?php

namespace MNGame\Controller\Panel\Crud;

use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use MNGame\Database\Entity\Parameter;
use MNGame\Enum\ParameterEnum;
use MNGame\Field\CKEditorField;
use ReflectionException;

class ParameterCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Parameter::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_DETAIL, Action::DELETE);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Konfiguracja')
            ->setEntityLabelInPlural('Konfiguracja')
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        return $this->get(EntityRepository::class)
            ->createQueryBuilder($searchDto, $entityDto, $fields, $filters)
            ->andWhere('entity.editable = true')
            ->orderBy('entity.order, entity.name', 'ASC');
    }

    /**
     * @throws ReflectionException
     */
    public function configureFields(string $pageName): iterable
    {
        if ($pageName === Crud::PAGE_INDEX) {
            return [
                TextField::new('name', 'Nazwa'),
                TextField::new('value', 'Wartość'),
            ];
        }

        if ($pageName === Crud::PAGE_EDIT) {
            return [
                TextField::new('name', 'Nazwa')
                    ->setFormTypeOption('disabled','disabled'),
                CKEditorField::new('value', 'Opis')->hideOnIndex(),
            ];
        }

        return [
            ChoiceField::new('name', 'Nazwa')
                ->setChoices(ParameterEnum::toArray()),
            TextField::new('value', 'Wartość'),
        ];
    }
}
