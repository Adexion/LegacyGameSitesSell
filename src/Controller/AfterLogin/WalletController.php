<?php

namespace ModernGame\Controller\AfterLogin;

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
            "cash" => $wallet->changeCash(0),
        ]);
    }

    public function microSMSExecute(Request $request, WalletService $wallet, MicroSMSService $payment)
    {
        $code = $request->request->get('smsCode');

        return new JsonResponse([
            "cash" => $wallet->changeCash(
                $payment->executePayment($code) * ($this->getParameter('multiplier') ?? 1)
            ),
        ]);
    }

    public function paypalExecute(Request $request, WalletService $wallet, PayPalService $payment)
    {
        $paymentId = $request->request->get('paymentID');
        $payerId = $request->request->get('payerID');

        return new JsonResponse([
            "cash" => $wallet->changeCash(
                $payment->executePayment($paymentId, $payerId) * ($this->getParameter('multiplier') ?? 1)
            ),
        ]);
    }
}
