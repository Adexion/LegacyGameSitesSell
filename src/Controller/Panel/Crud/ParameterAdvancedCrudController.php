<?php

namespace MNGame\Controller\Panel\Crud;

use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use MNGame\Database\Entity\Parameter;
use ReflectionException;

class ParameterAdvancedCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Parameter::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Konfiguracja Zaawansowana')
            ->setEntityLabelInPlural('Konfiguracja Zaawansowana')
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        return $this->get(EntityRepository::class)
            ->createQueryBuilder($searchDto, $entityDto, $fields, $filters)
            ->andWhere('entity.editable = true')
            ->andWhere('entity.multiple = true')
            ->orderBy('entity.order, entity.name', 'ASC');
    }

    public function createEntity(string $entityFqcn): Parameter
    {
        $entity = new Parameter();
        $entity->setEditable(true);
        $entity->setMultiple(true);
        $entity->setOrder(0);

        return $entity;
    }

    public function configureFields(string $pageName): iterable
    {
        if ($pageName === Crud::PAGE_INDEX) {
            return [
                TextField::new('name', 'Nazwa'),
                TextField::new('value', 'Wartość'),
            ];
        }

        return [
            TextField::new('name', 'Nazwa')
                ->setFormTypeOption('disabled', 'disabled'),
            TextField::new('value', 'Opis'),
        ];
    }
}
