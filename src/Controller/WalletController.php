<?php

namespace ModernGame\Controller;

use ModernGame\Service\User\WalletService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class WalletController extends Controller
{
    public function cash(Request $request, WalletService $wallet)
    {
        return new JsonResponse([
            "cash" => $wallet->changeCash(
                $this->getUser()->getId(),
                $request->request->get('load') ?? 0
            ),
        ]);
    }

    public function microSMSExecute(Request $request, WalletService $wallet)
    {
        $code = $request->request->get('smsCode');

        return new JsonResponse([
            "cash" => $wallet->changeCash(
                $this->getUser()->getId(),
                1
//                (float)$payment->executePayment($code) * 100
            ),
        ]);
    }

    public function paypalExecute(Request $request, WalletService $wallet)
    {
        $paymentId = $request->request->get('paymentID');
        $payerId = $request->request->get('payerID');

        return new JsonResponse([
            "cash" => $wallet->changeCash(
                $this->getUser()->getId(),
                1
//                (float)$payment->executePayment($paymentId, $payerId) * 100
            ),
        ]);
    }
}
