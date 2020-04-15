<?php

namespace ModernGame\Service\Connection\Minecraft;

class RCONConnection
{
    private string $host;
    private string $port;
    private string $password;
    private int $timeout;
    private $socket;
    private bool $authorized = false;
    private string $lastResponse;
    private bool $isProd;

    const PACKET_AUTHORIZE = 5;
    const PACKET_COMMAND = 6;
    const SERVER_DATA_AUTH = 3;
    const SERVER_DATA_AUTH_RESPONSE = 2;
    const SERVER_DATA_EXEC_COMMAND = 2;
    const SERVER_DATA_RESPONSE_VALUE = 0;

    public function __construct(string $host, string $port, string $password, bool $isProd, int $timeout = 10)
    {
        $this->host = $host;
        $this->port = $port;
        $this->password = $password;
        $this->timeout = $timeout;
        $this->isProd = $isProd;
    }

    public function getResponse()
    {
        if (!$this->isProd) {
            return 'Mock execution done!';
        }

        return $this->lastResponse;
    }

    public function connect()
    {
        if (!$this->isProd) {
            return false;
        }

        $this->socket = fsockopen($this->host, $this->port, $errno, $errStr, $this->timeout);
        if (!$this->socket) {
            $this->lastResponse = $errStr;
            return false;
        }

        stream_set_timeout($this->socket, 3, 0);

        return $this->authorize();
    }

    public function disconnect()
    {
        if ($this->socket) {
            fclose($this->socket);
        }
    }

    public function isConnected()
    {
        return $this->authorized;
    }

    public function sendCommand($command)
    {
        if (!$this->isConnected()) {
            return false;
        }

        $this->writePacket(self::PACKET_COMMAND, self::SERVER_DATA_EXEC_COMMAND, $command);
        $response_packet = $this->readPacket();

        if ((int)$response_packet['id'] === self::PACKET_COMMAND) {
            if ((int)$response_packet['type'] === self::SERVER_DATA_RESPONSE_VALUE) {
                $this->lastResponse = $response_packet['body'];

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
        $size_data = fread($this->socket, 4);
        $size_pack = unpack('V1size', $size_data);
        $size = $size_pack['size'];

        $packet_data = fread($this->socket, $size);
        $packet_pack = unpack('V1id/V1type/a*body', $packet_data);

        return $packet_pack;
    }
}
