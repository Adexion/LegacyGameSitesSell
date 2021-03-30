<?php

namespace MNGame\Controller\Panel\Crud;

use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use MNGame\Database\Entity\User;
use MNGame\Predicate\RolePredicate;
use Symfony\Component\Security\Core\Security;

abstract class AbstractRoleAccessCrudController extends AbstractCrudController
{
    protected Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder {
        $response = $this->get(EntityRepository::class)->createQueryBuilder($searchDto, $entityDto, $fields, $filters);
        if (RolePredicate::isAdminRoleGranted($this->security)) {
            return $response;
        }

        /** @var User $user */
        $user = $this->security->getUser();

        return $response
            ->andWhere('entity.serverId = :serverId')
            ->setParameter(':serverId', $user->getAssignedServerId() ?: 0);
    }
}