<?php

namespace MNGame\Service\Connection\Payment\Client;

use Doctrine\Common\Collections\Collection;
use GuzzleHttp\Exception\GuzzleException;
use MNGame\Database\Repository\SMSPriceRepository;
use MNGame\Exception\ContentException;
use MNGame\Service\Connection\ApiClient\RestApiClient;
use MNGame\Service\EnvironmentService;

class HotPaySmsPaymentClient extends RestApiClient implements PaymentClientInterface
{
    private const URL = 'https://api.hotpay.pl/check_sms.php?';
    private SMSPriceRepository $smsPriceRepository;
    private Collection $paymentConfiguration;

    public function __construct(
        SMSPriceRepository $smsPriceRepository,
        Collection $paymentConfiguration,
        EnvironmentService $env,
        ?string $className = null
    ) {
        parent::__construct($env, $className);
        $this->paymentConfiguration = $paymentConfiguration;
        $this->smsPriceRepository = $smsPriceRepository;
    }

    /**
     * @throws ContentException
     * @throws GuzzleException
     */
    public function executeRequest(array $data)
    {
        $request = [
            'secret' => $this->paymentConfiguration->get('secret'),
            'code' => $data['paymentId'],
        ];

        $response = json_decode($this->request(RestApiClient::GET, self::URL.http_build_query($request)), true);
        $this->handleError($response);

        return $this->smsPriceRepository->findOneBy(['id' => $this->paymentConfiguration->get('secret')])->getAmount();
    }

    /**
     * @throws ContentException
     */
    protected function handleError(array $response)
    {
        if (empty($response)) {
            throw new ContentException(['error' => 'Nie można nawiązać połączenia z serwerem płatności.']);
        }

        if (isset($response['tresc'])) {
            throw new ContentException(['error' => $response['tresc']]);
        }

        if (isset($response['aktywacja']) && (int)$response['aktywacja'] > 1) {
            throw new ContentException(['smsCode' => 'Przesłany kod jest nieprawidłowy.']);
        }
    }
}
