<?php

namespace MNGame\Controller\Panel;

use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use MNGame\Controller\Panel\Crud\AdminServerUserCrudController;
use MNGame\Controller\Panel\Crud\ArticleCrudController;
use MNGame\Controller\Panel\Crud\FAQCrudController;
use MNGame\Controller\Panel\Crud\ItemCrudController;
use MNGame\Controller\Panel\Crud\ItemListCrudController;
use MNGame\Controller\Panel\Crud\PaySafeCardCrudController;
use MNGame\Controller\Panel\Crud\PriceCrudController;
use MNGame\Controller\Panel\Crud\RegulationCategoryCrudController;
use MNGame\Controller\Panel\Crud\RegulationCrudController;
use MNGame\Controller\Panel\Crud\TicketCrudController;
use MNGame\Controller\Panel\Crud\TutorialCrudController;
use MNGame\Controller\Panel\Crud\UserCrudController;
use MNGame\Controller\Panel\Crud\UserItemCrudController;
use MNGame\Controller\Panel\Crud\WalletCrudController;
use MNGame\Database\Entity\AdminServerUser;
use MNGame\Database\Entity\Article;
use MNGame\Database\Entity\FAQ;
use MNGame\Database\Entity\Item;
use MNGame\Database\Entity\ItemList;
use MNGame\Database\Entity\PaySafeCard;
use MNGame\Database\Entity\Price;
use MNGame\Database\Entity\Regulation;
use MNGame\Database\Entity\RegulationCategory;
use MNGame\Database\Entity\Ticket;
use MNGame\Database\Entity\Tutorial;
use MNGame\Database\Entity\User;
use MNGame\Database\Entity\UserItem;
use MNGame\Database\Entity\Wallet;
use MNGame\Enum\RolesEnum;
use Symfony\Component\Security\Core\User\UserInterface;

trait MainDashboardController
{
    public function configureAssets(): Assets
    {
        return Assets::new()
            ->addCssFile('https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.css');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linktoRoute('Strona główna', 'fas fa-home', 'index'),

            MenuItem::section('Sprzedaż')
                ->setPermission(RolesEnum::ROLE_ADMIN),

            MenuItem::linktoRoute('Statystyki Sprzedaży', 'fas fa-chart-pie', 'shopStatistic')
                ->setPermission(RolesEnum::ROLE_ADMIN),
            MenuItem::linktoRoute('Statystyki Płatności', 'fas fa-chart-pie', 'paymentStatistic')
                ->setPermission(RolesEnum::ROLE_ADMIN),
            MenuItem::linkToCrud('PaySafeCard', 'fa fa-lock', PaySafeCard::class)
                ->setController(PaySafeCardCrudController::class)
                ->setPermission(RolesEnum::ROLE_ADMIN),
            MenuItem::linkToCrud('Cennik SMS', 'fa fa-tags', Price::class)
                ->setController(PriceCrudController::class)
                ->setPermission(RolesEnum::ROLE_ADMIN),

            MenuItem::section('ItemShop'),

            MenuItem::linkToCrud('Przedmioty', 'fas fa-cube', Item::class)
                ->setController(ItemCrudController::class)
                ->setPermission(RolesEnum::ROLE_SERVER),
            MenuItem::linkToCrud('Listy Przedmiotów', 'fas fa-cubes', ItemList::class)
                ->setController(ItemListCrudController::class)
                ->setPermission(RolesEnum::ROLE_SERVER),

            MenuItem::section('Tekstowe')
                ->setPermission(RolesEnum::ROLE_MODERATOR)
                ->setPermission(RolesEnum::ROLE_SERVER),

            MenuItem::linkToCrud('Artykuły', 'fa fa-newspaper', Article::class)
                ->setController(ArticleCrudController::class)
                ->setPermission(RolesEnum::ROLE_SERVER),
            MenuItem::linkToCrud('FAQ', 'fa fa-question-circle', FAQ::class)
                ->setController(FAQCrudController::class)
                ->setPermission(RolesEnum::ROLE_MODERATOR),
            MenuItem::linkToCrud('Poradniki', 'fa fa-chalkboard-teacher', Tutorial::class)
                ->setController(TutorialCrudController::class)
                ->setPermission(RolesEnum::ROLE_MODERATOR),
            MenuItem::linkToCrud('Zasady', 'fas fa-ruler-vertical', Regulation::class)
                ->setController(RegulationCrudController::class)
                ->setPermission(RolesEnum::ROLE_MODERATOR),
            MenuItem::linkToCrud('Kategorie Regulaminu', 'fas fa-pencil-ruler', RegulationCategory::class)
                ->setController(RegulationCategoryCrudController::class)
                ->setPermission(RolesEnum::ROLE_MODERATOR),

            MenuItem::section('Użytkownik')
                ->setPermission(RolesEnum::ROLE_SERVER),

            MenuItem::linkToCrud('Użytkownicy', 'fa fa-users', User::class)
                ->setController(UserCrudController::class)
                ->setPermission(RolesEnum::ROLE_SERVER),
            MenuItem::linkToCrud('Admini na stronie', 'fa fa-users-cog', AdminServerUser::class)
                ->setController(AdminServerUserCrudController::class)
                ->setPermission(RolesEnum::ROLE_SERVER),
            MenuItem::linkToCrud('Przedmioty Użytkowników', 'fa fa-shopping-bag', UserItem::class)
                ->setController(UserItemCrudController::class)
                ->setPermission(RolesEnum::ROLE_SERVER),
            MenuItem::linkToCrud('Portfele', 'fa fa-wallet', Wallet::class)
                ->setController(WalletCrudController::class)
                ->setPermission(RolesEnum::ROLE_ADMIN),
            MenuItem::linkToCrud('Wiadomości', 'fa fa-reply', Ticket::class)
                ->setController(TicketCrudController::class)
                ->setPermission(RolesEnum::ROLE_MODERATOR)
                ->setPermission(RolesEnum::ROLE_SERVER),
            MenuItem::linktoRoute('Mailing', 'fas fa-envelope', 'mailing')
                ->setPermission(RolesEnum::ROLE_MODERATOR)
                ->setPermission(RolesEnum::ROLE_SERVER),

            MenuItem::section(),

            MenuItem::linkToLogout('Wyloguj', 'fas fa-sign-out-alt'),
        ];
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
