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

class PrepaidController extends Controller
{
    /**
     * Getting a prepaid status.
     *
     * Getting status of prepaid account. Shows how many moneys did you spend.
     *
     * @SWG\Tag(name="Prepaid")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(
     *              type="integer",
     *              property="cash"
     *          )
     *      )
     * )
     */
    public function cash(WalletService $wallet)
    {
        return new JsonResponse([
            "cash" => $wallet->changeCash(0),
        ]);
    }

    /**
     *  Charge your prepaid account with SMS
     *
     * @SWG\Tag(name="Prepaid")
     * @SWG\Parameter(
     *     type="object",
     *     in="body",
     *     name="JSON",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(
     *              type="string",
     *              property="smsCode"
     *          )
     *     )
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(
     *              type="integer",
     *              property="cash"
     *          )
     *      )
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
     *  Charge your prepaid account with paypal
     *
     * @SWG\Tag(name="Prepaid")
     * @SWG\Parameter(
     *     type="object",
     *     in="body",
     *     name="JSON",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(
     *              type="string",
     *              property="paymentId"
     *          ),
     *          @SWG\Property(
     *              type="string",
     *              property="payerId"
     *          )
     *     )
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(
     *              type="integer",
     *              property="cash"
     *          )
     *      )
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
     *  Charge your prepaid account with DotPay
     *
     * @SWG\Tag(name="Prepaid")
     * @SWG\Parameter(
     *     type="object",
     *     in="body",
     *     name="JSON",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(
     *              type="string",
     *              property="paymentId"
     *          )
     *     )
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(
     *              type="integer",
     *              property="cash"
     *          )
     *      )
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
