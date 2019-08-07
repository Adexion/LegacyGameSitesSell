<?php

namespace ModernGame\Controller;

use ModernGame\Service\Connection\Payment\MicroSMS\MicroSMSService;
use ModernGame\Service\Connection\Payment\PayPal\PayPalService;
use ModernGame\Service\User\WalletService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class WalletController extends Controller
{
    public function cash(WalletService $wallet)
    {
        return new JsonResponse([
            "cash" => $wallet->changeCash(
                $this->getUser()->getId(),
                0
            ),
        ]);
    }

    public function microSMSExecute(Request $request, WalletService $wallet, MicroSMSService $payment)
    {
        $code = $request->request->get('smsCode');

        return new JsonResponse([
            "cash" => $wallet->changeCash(
                $this->getUser()->getId(),
                (float)$payment->executePayment($code) * 100
            ),
        ]);
    }

    public function paypalExecute(Request $request, WalletService $wallet, PayPalService $payment)
    {
        $paymentId = $request->request->get('paymentID');
        $payerId = $request->request->get('payerID');

        return new JsonResponse([
            "cash" => $wallet->changeCash(
                $this->getUser()->getId(),
                (float)$payment->executePayment($paymentId, $payerId) * 100
            ),
        ]);
    }
}
