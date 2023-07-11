<?php

/**
 * This file is part of the bugloos/error-response-bundle project.
 * (c) Bugloos <https://bugloos.com/>
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Bugloos\ErrorResponseBundle\Normalizer;

use Bugloos\ErrorResponseBundle\Exception\FormException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @author Mojtaba Gheytasi <mjgheytasi@gmail.com>
 */
class FormExceptionNormalizer implements NormalizerInterface
{
    public function normalize($exception, $format = null, array $context = []): array
    {
        $formattedErrors = [];

        foreach ($exception->getErrors() as $error) {
            $cause = $error->getCause();

            if ($cause && method_exists($cause, 'getPropertyPath') && $cause->getPropertyPath()) {
                $path = preg_replace('/^(data.)|(.data)|(\\])|(\\[)|children/', '', $cause->getPropertyPath());
                $formattedErrors[$path] = $error->getMessage();
            } else {
                $formattedErrors[] = $error->getMessage();
            }
        }

        return $formattedErrors;
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof FormException;
    }
}
