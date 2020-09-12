<?php


namespace ModernGame\Controller\Panel;

use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController as AbstractBasicDashboardController;
use ModernGame\Controller\Panel\Crud\ArticleCrudController;
use ModernGame\Controller\Panel\Crud\ItemCrudController;
use ModernGame\Controller\Panel\Crud\ItemListCrudController;
use ModernGame\Controller\Panel\Crud\ModListCrudController;
use ModernGame\Controller\Panel\Crud\PaySafeCardCrudController;
use ModernGame\Controller\Panel\Crud\PriceCrudController;
use ModernGame\Controller\Panel\Crud\RegulationCategoryCrudController;
use ModernGame\Controller\Panel\Crud\RegulationCrudController;
use ModernGame\Controller\Panel\Crud\TicketCrudController;
use ModernGame\Controller\Panel\Crud\UserCrudController;
use ModernGame\Controller\Panel\Crud\UserItemCrudController;
use ModernGame\Controller\Panel\Crud\WalletCrudController;
use ModernGame\Database\Entity\Item;
use ModernGame\Database\Entity\ItemList;
use ModernGame\Database\Entity\ModList;
use ModernGame\Database\Entity\PaySafeCard;
use ModernGame\Database\Entity\Price;
use ModernGame\Database\Entity\Regulation;
use ModernGame\Database\Entity\RegulationCategory;
use ModernGame\Database\Entity\User;
use ModernGame\Database\Entity\UserItem;
use ModernGame\Database\Entity\Wallet;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class AbstractDashboardController extends AbstractBasicDashboardController
{
    public function configureAssets(): Assets
    {
        return Assets::new()
            ->addCssFile('https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.css');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::section('Wykresy'),

            MenuItem::linktoRoute('Statystyki Sprzedaży', 'fas fa-chart-pie', 'shopStatistic'),
            MenuItem::linktoRoute('Statystyki Płatności', 'fas fa-chart-pie', 'paymentStatistic'),

            MenuItem::section('ItemShop'),

            MenuItem::linkToCrud('Przedmioty', 'fas fa-cube', Item::class)
                ->setController(ItemCrudController::class),
            MenuItem::linkToCrud('ListyPrzedmiotów', 'fas fa-cubes', ItemList::class)
                ->setController(ItemListCrudController::class),
            MenuItem::linkToCrud('Cennik SMS', 'fa fa-tags', Price::class)
                ->setController(PriceCrudController::class),
            MenuItem::linkToCrud('PaySafeCard', 'fa fa-lock', PaySafeCard::class)
                ->setController(PaySafeCardCrudController::class),

            MenuItem::section('Tekstowe'),

            MenuItem::linkToCrud('Artykuły', 'fa fa-newspaper', Article::class)
                ->setController(ArticleCrudController::class),
            MenuItem::linkToCrud('Lista Modyfikacji', 'fa fa-wrench', ModList::class)
                ->setController(ModListCrudController::class),
            MenuItem::linkToCrud('Zasady', 'fas fa-ruler-vertical', Regulation::class)
                ->setController(RegulationCrudController::class),
            MenuItem::linkToCrud('Kategorie Regulaminu', 'fas fa-pencil-ruler', RegulationCategory::class)
                ->setController(RegulationCategoryCrudController::class),

            MenuItem::section('Użytkownik'),

            MenuItem::linkToCrud('Użytkownicy', 'fa fa-users', User::class)
                ->setController(UserCrudController::class),
            MenuItem::linkToCrud('Przedmioty Użytkowników', 'fa fa-shopping-bag', UserItem::class)
                ->setController(UserItemCrudController::class),
            MenuItem::linkToCrud('Portfele', 'fa fa-wallet', Wallet::class)
                ->setController(WalletCrudController::class),
            MenuItem::linkToCrud('Wiadomości', 'fa fa-reply', Ticket::class)
                ->setController(TicketCrudController::class),

            MenuItem::section(),

            MenuItem::linkToLogout('Wyloguj', 'fas fa-sign-out-alt')
        ];
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        /** @var User $user */
        return parent::configureUserMenu($user)
            ->setName($user->getUsername())
            ->setAvatarUrl(sprintf('http://cravatar.eu/avatar/%s/64.png', $this->getUser()->getUsername()))
            ->displayUserName(true)
            ->displayUserAvatar(true);
    }
}
