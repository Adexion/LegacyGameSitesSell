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
use MNGame\Database\Entity\SMSPrice;
use MNGame\Database\Entity\Regulation;
use MNGame\Database\Entity\RegulationCategory;
use MNGame\Database\Entity\Ticket;
use MNGame\Database\Entity\Tutorial;
use MNGame\Database\Entity\UserItem;
use MNGame\Database\Entity\Wallet;
use MNGame\Enum\RolesEnum;

class ModuleProvider
{
    private array $data;

    public function __construct()
    {
        $this->data = [
            'index' => [],
            'console' => [
                'menuLinks' => [
                    MenuItem::linktoRoute('Terminal', 'fas fa-terminal', 'console')
                        ->setPermission(RolesEnum::ROLE_SERVER),

                    MenuItem::section(),
                ]
            ],
            'article' => [
                'menuLinks' => [
                    MenuItem::linkToCrud('Artykuły', 'fa fa-newspaper', Article::class)
                        ->setController(ArticleCrudController::class)
                        ->setPermission(RolesEnum::ROLE_SERVER),
                ],
                'name' => 'Artykuły',
                'icon' => 'fas fa-list',
                'route' => 'show-article-list'
            ],
            'tutorial' => [
                'menuLinks' => [
                    MenuItem::linkToCrud('Poradniki', 'fa fa-chalkboard-teacher', Tutorial::class)
                        ->setController(TutorialCrudController::class)
                        ->setPermission(RolesEnum::ROLE_MODERATOR),
                ],
                'name' => 'Poradniki',
                'icon' => 'fas fa-film',
                'route' => 'tutorial'
            ],
            'rule' => [
                'menuLinks' => [
                    MenuItem::linkToCrud('Kategorie Regulaminu', 'fas fa-pencil-ruler', RegulationCategory::class)
                        ->setController(RegulationCategoryCrudController::class)
                        ->setPermission(RolesEnum::ROLE_MODERATOR),
                    MenuItem::linkToCrud('Zasady', 'fas fa-ruler-vertical', Regulation::class)
                        ->setController(RegulationCrudController::class)
                        ->setPermission(RolesEnum::ROLE_MODERATOR),
                ],
                'name' => 'Zasady',
                'icon' => 'fas fa-ruler-vertical',
                'route' => 'rule'
            ],
            'faq' => [
                'menuLinks' => [
                    MenuItem::linkToCrud('FAQ', 'fa fa-question-circle', FAQ::class)
                        ->setController(FAQCrudController::class)
                        ->setPermission(RolesEnum::ROLE_MODERATOR),

                    MenuItem::section(),
                ],
                'name' => 'FAQ',
                'icon' => 'fas fa-question',
                'route' => 'faq'
            ],
            'contact' => [
                'menuLinks' => [
                    MenuItem::linkToCrud('Wiadomości', 'fa fa-reply', Ticket::class)
                        ->setController(TicketCrudController::class)
                        ->setPermission(RolesEnum::ROLE_MODERATOR)
                        ->setPermission(RolesEnum::ROLE_SERVER),
                    MenuItem::linktoRoute('Mailing', 'fas fa-envelope', 'mailing')
                        ->setPermission(RolesEnum::ROLE_MODERATOR)
                        ->setPermission(RolesEnum::ROLE_SERVER),

                    MenuItem::section(),
                ],
                'name' => 'Kontakt',
                'icon' => 'fas fa-book',
                'route' => 'contact'
            ],
            'itemShop' => [
                'menuLinks' => [
                    MenuItem::linktoRoute('Statystyki Sprzedaży', 'fas fa-chart-pie', 'shopStatistic')
                        ->setPermission(RolesEnum::ROLE_ADMIN),
                    MenuItem::linktoRoute('Statystyki Płatności', 'fas fa-chart-pie', 'paymentStatistic')
                        ->setPermission(RolesEnum::ROLE_ADMIN),
                    MenuItem::linkToCrud('Cennik SMS', 'fa fa-tags', SMSPrice::class)
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
                'name' => 'ItemShop',
                'icon' => 'fas fa-shopping-cart',
                'route' => 'item-shop'
            ],
        ];
    }

    public function getModules(): array
    {
        return $this->data;
    }

    public function getModuleNameByRoute($route): string {
        foreach ($this->data as $key => $value) {
            if (isset($value['route']) && $value['route'] === $route) {
                return $key;
            }
        }

        return 'index';
    }
}