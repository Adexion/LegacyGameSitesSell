<?php

namespace ModernGame\Serializer;

class SerializedDto
{
    private $serialized;

    public function __construct(string $serialized)
    {
        $this->serialized = $serialized;
    }

    public function getString(): string
    {
       return $this->serialized;
    }

    public function getArray(): array
    {
        return json_decode($this->serialized, true);
    }
}
