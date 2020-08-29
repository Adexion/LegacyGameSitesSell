<?php

namespace ModernGame\Controller\Front;

use ModernGame\Database\Entity\ItemList;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ItemsShopController extends AbstractController
{
    /**
     * @Route(name="item-shop", path="/itemshop")
     */
    public function itemShop()
    {
        return $this->render('front/page/itemshop.html.twig', [
            'itemLists' => $this->getDoctrine()->getRepository(ItemList::class)->findAll(),
            'paypalClient' => $this->getParameter('paypal')['client']
        ]);
    }
}
