<?php

namespace ModernGame\Serializer;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class CustomNormalizer implements ContextAwareNormalizerInterface
{
    private $router;
    private $normalizer;

    public function __construct(UrlGeneratorInterface $router, ObjectNormalizer $normalizer)
    {
        $this->router = $router;
        $this->normalizer = $normalizer;
    }

    public function normalize($topic, $format = null, array $context = [])
    {
        return $this->normalizer->normalize(
            $topic,
            $format,
            array_merge($context, ['ignored_attributes' => ["__initializer__", "__cloner__", "__isInitialized__"]])
        );
    }

    public function supportsNormalization($data, $format = null, array $context = [])
    {
        return true;
    }
}
