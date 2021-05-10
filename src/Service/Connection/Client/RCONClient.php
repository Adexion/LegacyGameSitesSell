<?php

namespace MNGame\Service\Connection\Client;

use Exception;
use MNGame\Database\Entity\Server;

class RCONClient implements ClientInterface
{
    private ?string $host;
    private ?string $port;
    private ?string $password;
    private $socket;
    private string $lastResponse;

    private const PACKET_AUTHORIZE = 5;
    private const PACKET_COMMAND = 6;
    private const SERVER_DATA_AUTH_RESPONSE = 2;
    private const SERVER_DATA_EXEC_COMMAND = 2;
    private const SERVER_DATA_RESPONSE_VALUE = 0;
    private const SERVER_DATA_AUTH = 3;
    private bool $authorized = false;

    public function __construct(Server $server)
    {
        $this->host = $server->getHost();
        $this->port = $server->getPort();
        $this->password = $server->getPassword();
    }

    public function getResponse(): string
    {
        return $this->lastResponse ?? '';
    }

    public function connect(): bool
    {
        $this->socket = fsockopen($this->host, $this->port, $errno, $errStr) ?: null;
        if (!$this->socket) {
            $this->lastResponse = $errStr;
            return false;
        }

        stream_set_timeout($this->socket, 3, 0);

        return $this->authorize();
    }

    public function disconnect(): bool
    {
        if ($this->socket) {
            return fclose($this->socket);
        }

        return false;
    }

    public function isConnected(): bool
    {
        return $this->authorized;
    }

    public function sendCommand(string $message): bool
    {
        if (!$this->isConnected()) {
            return false;
        }

        $this->writePacket(self::PACKET_COMMAND, self::SERVER_DATA_EXEC_COMMAND, $message);

        return $this->readPackets();
    }

    private function readPackets(): bool
    {
        $response_packet = $this->readPacket();

        if ((int)$response_packet['id'] === self::PACKET_COMMAND) {
            if ((int)$response_packet['type'] === self::SERVER_DATA_RESPONSE_VALUE) {
                $this->lastResponse = $response_packet['body'];
                if (empty($response_packet['body']) && empty($this->lastResponse)) {
                    $this->lastResponse = 'Wykonano!';
                    return $this->readPackets();
                }

                return $response_packet['body'];
            }
        }

        return false;
    }

    private function authorize(): bool
    {
        $this->writePacket(self::PACKET_AUTHORIZE, self::SERVER_DATA_AUTH, $this->password);
        $response_packet = $this->readPacket();

        if ((int)$response_packet['type'] === self::SERVER_DATA_AUTH_RESPONSE) {
            if ((int)$response_packet['id'] === self::PACKET_AUTHORIZE) {
                $this->authorized = true;

                return true;
            }
        }

        $this->disconnect();

        return false;
    }

    private function writePacket($packetId, $packetType, $packetBody)
    {
        $packet = pack('VV', $packetId, $packetType);
        $packet = $packet . $packetBody . "\x00";
        $packet = $packet . "\x00";

        $packet_size = strlen($packet);

        $packet = pack('V', $packet_size) . $packet;

        fwrite($this->socket, $packet, strlen($packet));
    }

    private function readPacket()
    {
        try {
            $size_data = fread($this->socket, 4);
            $size_pack = unpack('V1size', $size_data);
            $size = $size_pack['size'];
        } catch (Exception $exception) {
            return $this->readPacket();
        }

        $packet_data = fread($this->socket, $size);

        return unpack('V1id/V1type/a*body', $packet_data);
    }
}
