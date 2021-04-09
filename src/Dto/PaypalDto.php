<?php

namespace MNGame\Dto;

class PaypalDto
{
    private ?string $client = null;
    private ?string $secret = null;

    public function getClient(): ?string
    {
        return $this->client;
    }

    public function setClient(?string $client)
    {
        $this->client = $client;
    }

    public function getSecret(): ?string
    {
        return $this->secret;
    }

    public function setSecret(?string $secret)
    {
        $this->secret = $secret;
    }

    public function toArray(): array {
        return [
            'client' => $this->client,
            'secret' => $this->secret
        ];
    }
}