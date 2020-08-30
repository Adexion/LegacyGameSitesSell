<?php

namespace ModernGame\Controller\Front;

use ModernGame\Database\Entity\ItemList;
use ModernGame\Database\Entity\Wallet;
use ModernGame\Database\Repository\ItemListRepository;
use ModernGame\Enum\PaymentTypeEnum;
use ModernGame\Service\Connection\Minecraft\RCONService;
use ModernGame\Service\User\WalletService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
            'paypalClient' => $this->getParameter('paypal')['client'],
            'wallet' => $this->getDoctrine()->getRepository(Wallet::class)->findOneBy(['user' => $this->getUser()])
        ]);
    }

    /**
     * @Route(name="wallet-payment", path="/prepaid/payment")
     */
    public function prepaidPayment(
        Request $request,
        ItemListRepository $itemListRepository,
        WalletService $walletService,
        RCONService $rcon
    ) {
        /** @var ItemList $itemList */
        $itemList = $itemListRepository->find($request->request->getInt('itemListId'));

        if ($this->predicate($itemList, $walletService)) {
            return $this->render('front/page/prepaidPayment.html.twig', [
                'error' => 'Kwota zakupu jest mniejsza niż kwota którą zapłacono.',
                'responseType' => PaymentTypeEnum::ERROR,
                'wallet' => $this->getDoctrine()->getRepository(Wallet::class)->findOneBy(['user' => $this->getUser()])
            ]);
        }

        $rcon->executeItemListInstant(
            $itemList->getAfterPromotionPrice(),
            $request->request->getInt('itemListId') ?? 0,
            $this->getUser()
        );
        $walletService->changeCash(-$itemList->getAfterPromotionPrice(), $this->getUser());

        return $this->render('front/page/prepaidPayment.html.twig', [
            'itemList' => $itemList,
            'responseType' => PaymentTypeEnum::SUCCESS,
            'wallet' => $this->getDoctrine()->getRepository(Wallet::class)->findOneBy(['user' => $this->getUser()])
        ]);
    }

    private function predicate(ItemList $itemList, WalletService $walletService)
    {
        return $itemList && $itemList->getAfterPromotionPrice() > $walletService->changeCash(0, $this->getUser());
    }
}
