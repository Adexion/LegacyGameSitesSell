<?php

namespace MNGame\Service\Route;

use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
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
use MNGame\Controller\Panel\Crud\UserItemCrudController;
use MNGame\Controller\Panel\Crud\WalletCrudController;
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
use MNGame\Database\Entity\UserItem;
use MNGame\Database\Entity\Wallet;
use MNGame\Enum\RolesEnum;

class RouterDataProvider
{
    private array $data;

    public function __construct()
    {
        $this->data = [
            'show-article-list' => [
                'name' => 'Artykuły',
                'icon' => 'fas fa-list',
                'menuLinks' => [
                    MenuItem::linkToCrud('Artykuły', 'fa fa-newspaper', Article::class)
                        ->setController(ArticleCrudController::class)
                        ->setPermission(RolesEnum::ROLE_SERVER),
                ]
            ],
            'tutorial-front' => [
                'name' => 'Poradniki',
                'icon' => 'fas fa-film',
                'menuLinks' => [
                    MenuItem::linkToCrud('Poradniki', 'fa fa-chalkboard-teacher', Tutorial::class)
                        ->setController(TutorialCrudController::class)
                        ->setPermission(RolesEnum::ROLE_MODERATOR),
                ]
            ],
            'rule' => [
                'name' => 'Zasady',
                'icon' => 'fas fa-ruler-vertical',
                'menuLinks' => [
                    MenuItem::linkToCrud('Kategorie Regulaminu', 'fas fa-pencil-ruler', RegulationCategory::class)
                        ->setController(RegulationCategoryCrudController::class)
                        ->setPermission(RolesEnum::ROLE_MODERATOR),
                    MenuItem::linkToCrud('Zasady', 'fas fa-ruler-vertical', Regulation::class)
                        ->setController(RegulationCrudController::class)
                        ->setPermission(RolesEnum::ROLE_MODERATOR),
                ]
            ],
            'faq-front' => [
                'name' => 'FAQ',
                'icon' => 'fas fa-question',
                'menuLinks' => [
                    MenuItem::linkToCrud('FAQ', 'fa fa-question-circle', FAQ::class)
                        ->setController(FAQCrudController::class)
                        ->setPermission(RolesEnum::ROLE_MODERATOR),

                    MenuItem::section(),
                ]
            ],
            'contact-front' => [
                'name' => 'Kontakt',
                'icon' => 'fas fa-book',
                'menuLinks' => [
                    MenuItem::linkToCrud('Wiadomości', 'fa fa-reply', Ticket::class)
                        ->setController(TicketCrudController::class)
                        ->setPermission(RolesEnum::ROLE_MODERATOR)
                        ->setPermission(RolesEnum::ROLE_SERVER),
                    MenuItem::linktoRoute('Mailing', 'fas fa-envelope', 'mailing')
                        ->setPermission(RolesEnum::ROLE_MODERATOR)
                        ->setPermission(RolesEnum::ROLE_SERVER),

                    MenuItem::section(),
                ]
            ],
            'item-shop' => [
                'name' => 'ItemShop',
                'icon' => 'fas fa-shopping-cart',
                'menuLinks' => [
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
                    MenuItem::linkToCrud('Przedmioty', 'fas fa-cube', Item::class)
                        ->setController(ItemCrudController::class)
                        ->setPermission(RolesEnum::ROLE_SERVER),
                    MenuItem::linkToCrud('Listy Przedmiotów', 'fas fa-cubes', ItemList::class)
                        ->setController(ItemListCrudController::class)
                        ->setPermission(RolesEnum::ROLE_SERVER),

                    MenuItem::section(),

                    MenuItem::linkToCrud('Portfele', 'fa fa-wallet', Wallet::class)
                        ->setController(WalletCrudController::class)
                        ->setPermission(RolesEnum::ROLE_ADMIN),
                    MenuItem::linkToCrud('Przedmioty Użytkowników', 'fa fa-shopping-bag', UserItem::class)
                        ->setController(UserItemCrudController::class)
                        ->setPermission(RolesEnum::ROLE_SERVER),

                    MenuItem::section(),
                ],
            ],
        ];
    }

    public function getRouteList(): array
    {
        return $this->data;
    }
}