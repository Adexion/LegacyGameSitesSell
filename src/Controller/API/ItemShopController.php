<?php

namespace MNGame\Controller\API;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use MNGame\Database\Entity\ItemList;
use MNGame\Database\Entity\Wallet;
use MNGame\Database\Repository\ItemListRepository;
use MNGame\Database\Repository\ItemListStatisticRepository;
use MNGame\Database\Repository\PaymentHistoryRepository;
use MNGame\Enum\PaymentTypeEnum;
use MNGame\Exception\ContentException;
use MNGame\Exception\PaymentProcessingException;
use MNGame\Service\Minecraft\ExecuteItemService;
use MNGame\Service\Payment\PaymentAcceptor;
use MNGame\Service\Payment\PaymentFormFactory;
use MNGame\Service\ServerProvider;
use MNGame\Service\User\WalletService;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ItemShopController extends AbstractController
{
    /**
     * @Route(name="api-item-shop", path="/api/itemlist", methods={"GET"})
     */
    public function itemShop(ServerProvider $serverProvider, PaymentHistoryRepository $paymentHistoryRepository, ItemListStatisticRepository $listStatisticRepository): Response
    {
        $server = $serverProvider->getSessionServer();

        return new JsonResponse([
            'serverName' => $server->getName(),
            'wallet' => $this->getDoctrine()->getRepository(Wallet::class)->findOneBy(['user' => $this->getUser()]),
            'amount' => $paymentHistoryRepository->getThisMonthMoney(),
            'lastBuyerList' => $listStatisticRepository->getStatistic(),
            'itemLists' => $this->getDoctrine()->getRepository(ItemList::class)->findBy(['serverId' => $server->getId()]),
        ]);
    }

    /**
     * @Route(name="api-item-shop-form", path="/api/item", methods={"GET"})
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ReflectionException
     */
    public function itemFormList(PaymentFormFactory $formFactory, ItemListRepository $itemListRepository, Request $request): Response
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new UnauthorizedHttpException('x-auth-token');
        }

        $itemId = $request->query->get('itemId');

        return new JsonResponse([
            'itemList' => $itemListRepository->find($itemId),
            'formList' => $formFactory->create('GS'.date('YmdHis'),),
        ]);
    }

    /**
     * @Route(name="api-wallet-page", path="/api/prepaid", methods={"POST"})
     *
     * @throws OptimisticLockException
     * @throws ORMException
     * @throws ReflectionException
     */
    public function wallet(Request $request, PaymentFormFactory $formFactory): Response
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new UnauthorizedHttpException('x-auth-token');
        }

        return new JsonResponse([
            'formList' => $formFactory->createFormList('GS'.date('YmdHis'), $request->request->get('price', 1) ?: 1),
            'wallet' => $this->getDoctrine()->getRepository(Wallet::class)->findOneBy(['user' => $this->getUser()]),
        ]);
    }

    /**
     * @Route(name="api-payment-accept", path="/api/payment", methods={"POST"})
     *
     * @throws ContentException
     * @throws ReflectionException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function paymentAccept(Request $request, ExecuteItemService $executeItemService, PaymentAcceptor $paymentAcceptor): Response
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new UnauthorizedHttpException('x-auth-token');
        }

        try {
            $paymentHistory = $paymentAcceptor->accept($request, PaymentTypeEnum::getValueByCamelCaseKey(ucfirst($request->request->get('paymentType'))));
        } catch (PaymentProcessingException) {
           return new JsonResponse([
                'responseType' => Response::HTTP_PAYMENT_REQUIRED,
            ]);
        }

        return new JsonResponse([
            'responseType' => $executeItemService->executeItem($paymentHistory->getUser(), $paymentHistory->getItemList()->getId()),
            'itemList' => $paymentHistory->getItemList(),
        ]);
    }

    /**
     * @Route(name="api-prepaid-status", path="/api/prepaid/payment/", methods={"POST"})
     *
     * @throws ContentException
     * @throws ORMException
     * @throws ReflectionException
     */
    public function prepaidStatus(Request $request, WalletService $walletService, PaymentAcceptor $paymentAcceptor): Response
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('login');
        }

        try {
            $paymentHistory = $paymentAcceptor->accept($request, PaymentTypeEnum::getValueByCamelCaseKey(ucfirst($request->request->get('paymentType'))));
        } catch (PaymentProcessingException) {
            return new JsonResponse([
                'responseType' => Response::HTTP_PAYMENT_REQUIRED,
            ]);
        }

        $walletService->changeCash($paymentHistory->getAmount(), $this->getUser());

        return new JsonResponse([
            'responseType' => Response::HTTP_OK,
        ]);
    }
}
