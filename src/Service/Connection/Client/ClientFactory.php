<?php

namespace MNGame\Service\Connection\Client;

use MNGame\Database\Entity\Server;
use MNGame\Enum\ExecutionTypeEnum;
use MNGame\Exception\ContentException;
use MNGame\Service\EnvironmentService;
use ReflectionException;

class ClientFactory
{
    public const SEVER_NOT_RESPONDING = 'Nie można nawiązać połączenia';

    private EnvironmentService $environmentService;

    public function __construct(EnvironmentService $environmentService)
    {
        $this->environmentService = $environmentService;
    }

    /**
     * @throws ContentException
     * @throws ReflectionException
     */
    public function create(Server $server): ?ClientInterface
    {
        /** @var ClientInterface $client */
        switch ($server->getExecutionType()->getValue() ?? ExecutionTypeEnum::WS) {
            case ExecutionTypeEnum::WS:
                $client = new WSClient($server);
                break;

            case ExecutionTypeEnum::RCON:
                $client = new RCONClient($server);
                break;

            default:
                return null;
        }

        @$client->connect();
        if (strpos($client->getResponse(), self::SEVER_NOT_RESPONDING) !== false) {
            throw new ContentException(['error' => 'Nie udało się połączyć z serwerem.']);
        }

        return $client;
    }
}
