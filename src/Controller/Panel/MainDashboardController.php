<?php

namespace MNGame\Controller\Panel;

use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use MNGame\Controller\Panel\Crud\AdminServerUserCrudController;
use MNGame\Controller\Panel\Crud\UserCrudController;
use MNGame\Database\Entity\AdminServerUser;
use MNGame\Service\Route\ModuleRouteBuilder;
use MNGame\Database\Entity\User;
use MNGame\Enum\RolesEnum;
use Symfony\Component\Security\Core\User\UserInterface;

trait MainDashboardController
{
    private ModuleRouteBuilder $builder;

    public function __construct(ModuleRouteBuilder $builder)
    {
        $this->builder = $builder;
    }

    public function configureAssets(): Assets
    {
        return Assets::new()
            ->addCssFile('https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.css');
    }

    public function configureMenuItems(): iterable
    {
        $data = array_merge([
                MenuItem::linktoRoute('Strona główna', 'fas fa-home', 'index'),

                MenuItem::section(),
            ], $this->builder->getData());

        return array_merge($data, [
                MenuItem::linkToCrud('Użytkownicy', 'fa fa-users', User::class)
                    ->setController(UserCrudController::class)
                    ->setPermission(RolesEnum::ROLE_SERVER),
                MenuItem::linkToCrud('Admini na stronie', 'fa fa-users-cog', AdminServerUser::class)
                    ->setController(AdminServerUserCrudController::class)
                    ->setPermission(RolesEnum::ROLE_SERVER),

                MenuItem::linkToLogout('Wyloguj', 'fas fa-sign-out-alt'),
            ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('MNGame');
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        /** @var User $user */
        return parent::configureUserMenu($user)
            ->setName($user->getUsername())
            ->setAvatarUrl(sprintf('https://cravatar.eu/avatar/%s/64.png', $this->getUser()->getUsername()))
            ->displayUserName(true)
            ->displayUserAvatar(true);
    }
}
