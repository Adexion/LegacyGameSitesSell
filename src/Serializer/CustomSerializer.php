<?php

namespace ModernGame\Serializer;

use Symfony\Component\Serializer\SerializerInterface;

class CustomSerializer
{
    const CONTEXT = ['ignored_attributes' => ["__initializer__", "__cloner__","__isInitialized__"]];

    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function serialize($data, $format): string
    {
        return $this->serializer->serialize($data, $format);
    }

    public function toArray($data): array
    {
        return json_decode($this->serializer->serialize($data, 'json', self::CONTEXT), true);
    }

    public function mergeDataWithEntity($entity, $data): array
    {
        return array_filter(
            array_merge($this->toArray($entity), $data)
        );
    }
}
