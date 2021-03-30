<?php

namespace MNGame\Predicate;

use MNGame\Enum\RolesEnum;
use Symfony\Component\Security\Core\Security;

class RolePredicate
{
    public static function isAdminRoleGranted(Security $security): bool
    {
        return $security->isGranted(RolesEnum::ROLE_ADMIN);
    }

    public static function isOnlyServerRoleGranted(Security $security): bool
    {
        return !$security->isGranted(RolesEnum::ROLE_ADMIN) && $security->isGranted(RolesEnum::ROLE_SERVER);
    }
}