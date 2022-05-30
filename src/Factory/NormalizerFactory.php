<?php

/**
 * This file is part of the bugloos/error-response-bundle project.
 * (c) Bugloos <https://bugloos.com/>
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Bugloos\ErrorResponseBundle\Factory;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @author Mojtaba Gheytasi <mjgheytasi@gmail.com>
 */
class NormalizerFactory
{
    private iterable $normalizers;

    public function __construct($normalizers)
    {
        $this->normalizers = $normalizers;
    }

    public function getNormalizer($data): ?NormalizerInterface
    {
        foreach ($this->normalizers as $normalizer) {
            if (
                $normalizer instanceof NormalizerInterface
                && $normalizer->supportsNormalization($data)
            ) {
                return $normalizer;
            }
        }

        return null;
    }
}
