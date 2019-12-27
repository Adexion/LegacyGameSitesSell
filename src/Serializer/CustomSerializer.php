<?php

namespace ModernGame\Serializer;

use Symfony\Component\Serializer\SerializerInterface;

class CustomSerializer
{
    const CONTEXT = ['ignored_attributes' => ["__initializer__", "__cloner__","__isInitialized__"]];

    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function serialize($data, $format = 'json', $context = []): SerializedDto
    {
        return new SerializedDto(
            $this->serializer->serialize($data, $format, array_merge_recursive($context, self::CONTEXT))
        );
    }

    public function mergeDataWithEntity($entity, $data): array
    {
        return array_filter(
            array_merge($this->serialize($entity)->getArray(), $data)
        );
    }
}
