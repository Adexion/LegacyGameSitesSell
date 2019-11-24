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

    public function serialize($data, $format = 'json', $context = null): SerializedDto
    {
        return new SerializedDto(
            $this->serializer->serialize($data, $format, $context ?? self::CONTEXT)
        );
    }

    public function mergeDataWithEntity($entity, $data): array
    {
        return array_filter(
            array_merge($this->serialize($entity)->getArray(), $data)
        );
    }
}
