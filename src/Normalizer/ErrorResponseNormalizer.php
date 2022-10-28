<?php

/**
 * This file is part of the bugloos/error-response-bundle project.
 * (c) Bugloos <https://bugloos.com/>
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Bugloos\ErrorResponseBundle\Normalizer;

use Bugloos\ErrorResponseBundle\Exception\FormException;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;

class ErrorResponseNormalizer extends AbstractErrorNormalizer implements CacheableSupportsMethodInterface
{
    /**
     * {@inheritdoc}
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        if (!$object instanceof FlattenException) {
            throw new InvalidArgumentException(sprintf('The object must implement "%s".', FlattenException::class));
        }
        $context += $this->defaultContext;
        $debug = $this->debug && ($context['debug'] ?? true);

        return $this->createResponse($debug, $object, $context);
    }

    /**
     * {@inheritdoc}
     *
     * @param array $context
     */
    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof FlattenException && !$context['exception'] instanceof FormException;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
