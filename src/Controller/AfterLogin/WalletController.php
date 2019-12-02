<?php

namespace ModernGame\Controller\AfterLogin;

use ModernGame\Service\Connection\Payment\DotPay\DotPayService;
use ModernGame\Service\Connection\Payment\MicroSMS\MicroSMSService;
use ModernGame\Service\Connection\Payment\PayPal\PayPalService;
use ModernGame\Service\User\WalletService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Swagger\Annotations as SWG;

class WalletController extends Controller
{
    /**
     * @SWG\Tag(name="Payment")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function cash(WalletService $wallet)
    {
        return new JsonResponse([
            "cash" => $wallet->changeCash(0),
        ]);
    }

    /**
     * @SWG\Tag(name="Payment")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function microSMSExecute(Request $request, WalletService $wallet, MicroSMSService $payment)
    {
        $code = $request->request->get('smsCode');

        return new JsonResponse([
            "cash" => $wallet->changeCash(
                $payment->executePayment($code) * ($this->getParameter('multiplier') ?? 1)
            ),
        ]);
    }

    /**
     * @SWG\Tag(name="Payment")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function paypalExecute(Request $request, WalletService $wallet, PayPalService $payment)
    {
        $paymentId = $request->request->get('paymentId');
        $payerId = $request->request->get('payerId');

        return new JsonResponse([
            "cash" => $wallet->changeCash(
                $payment->executePayment($paymentId, $payerId) * ($this->getParameter('multiplier') ?? 1)
            ),
        ]);
    }

    /**
     * @SWG\Tag(name="Payment")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function dotPayExecute(Request $request, WalletService $wallet, DotPayService $payment)
    {
        $paymentId = $request->request->get('paymentId');

        return new JsonResponse([
            "cash" => $wallet->changeCash(
                $payment->executePayment($paymentId) * ($this->getParameter('multiplier') ?? 1)
            ),
        ]);
    }
}
