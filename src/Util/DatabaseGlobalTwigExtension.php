<?php


namespace MNGame\Util;

use Doctrine\ORM\EntityManagerInterface;
use MNGame\Database\Entity\ModuleEnabled;
use MNGame\Database\Entity\Server;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class DatabaseGlobalTwigExtension extends AbstractExtension implements GlobalsInterface
{
    private EntityManagerInterface $em;
    private array $routes = [
        'show-article-list' => [
            'name' => 'Artykuły',
            'icon' => 'fas fa-list',
        ],
        'tutorial-front' => [

            'name' => 'Poradniki',
            'icon' => 'fas fa-film',
        ],
        'rule' => [
            'name' => 'Zasady',
            'icon' => 'fas fa-ruler-vertical',
        ],
        'faq-front' => [
            'name' => 'FAQ',
            'icon' => 'fas fa-question',
        ],
        'contact-front' => [
            'name' => 'Kontakt',
            'icon' => 'fas fa-book',
        ],
        'item-shop' => [
            'name' => 'ItemShop',
            'icon' => 'fas fa-shopping-cart',
        ],
    ];

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getGlobals(): array
    {
        $this->deactivateModuleLinks();

        return [
            'server' => $this->em->getRepository(Server::class)->findAll(),
            'module' => $this->routes,
        ];
    }

    private function deactivateModuleLinks()
    {
        $modulesEnabledList = $this->em->getRepository(ModuleEnabled::class)->findAll();

        /** @var ModuleEnabled $moduleEnabled */
        foreach ($modulesEnabledList as $moduleEnabled) {
            if (!$moduleEnabled->isActive()) {
                unset($this->routes[$moduleEnabled->getRoute()]);
            }
        }
    }
}