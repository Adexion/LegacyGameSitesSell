<?php

namespace MNGame\Serializer;

use Symfony\Component\Serializer\SerializerInterface;

class CustomSerializer
{
    private const CONTEXT = ['ignored_attributes' => ["__initializer__", "__cloner__", "__isInitialized__"]];
    private const DEFAULT_FORMAT = 'json';

    private SerializerInterface $serializer;
    private ?string $data = null;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function serialize($data, $format = self::DEFAULT_FORMAT, $context = []): self
    {
        $this->data = $this->serializer->serialize($data, $format, array_merge_recursive($context, self::CONTEXT));

        return $this;
    }

    public function mergeDataWithEntity($entity, $data): array
    {
        return array_merge($this->serialize($entity)->toArray(), array_filter($data));
    }

    public function getData()
    {
        return $this->data;
    }

    public function __toString(): string
    {
        return $this->data ?? '';
    }

    public function toArray(): array
    {
        return json_decode($this->data, true);
    }
}
