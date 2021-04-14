<?php

namespace MNGame\Command;

use Exception;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use SplObjectStorage;
use Symfony\Component\Console\Output\OutputInterface;

class MessageHandler implements MessageComponentInterface
{
    private const PASSWORD = "7d9aaEfbb73A";
    private const INVALID_PASSWORD = "Invalid password";
    private const INVALID_PASSWORD_TYPE = "invalid";

    private SplObjectStorage $connections;
    private OutputInterface $output;

    public function __construct(OutputInterface $output)
    {
        $this->connections = new SplObjectStorage();
        $this->output = $output;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->connections->attach($conn);

        $this->output->writeln('New connection established');
    }

    public function onMessage(ConnectionInterface $from, $msg): void
    {
        $message = json_decode($msg, true);
        if (($message['header']['password'] ?? null) !== self::PASSWORD) {
            $message['message'] = self::INVALID_PASSWORD;
            $message['type'] = self::INVALID_PASSWORD_TYPE;

            $from->send(json_encode($message));
            return;
        }

        foreach ($this->connections as $connection) {
            if (!($message['self'] ?? 0) && $from === $connection) {
                continue;
            }

            $connection->send($msg);
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->connections->detach($conn);
    }

    public function onError(ConnectionInterface $conn, Exception $e)
    {
        $this->connections->detach($conn);
        $conn->close();
    }
}