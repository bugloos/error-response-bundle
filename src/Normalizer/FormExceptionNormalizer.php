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

/**
 * @author Mojtaba Gheytasi <mjgheytasi@gmail.com>
 */
class FormExceptionNormalizer extends AbstractErrorNormalizer
{
    public function normalize($object, $format = null, array $context = []): array
    {
        if (!$object instanceof FlattenException || !$context['exception'] instanceof FormException) {
            throw new InvalidArgumentException(
                sprintf('The object exception must implement "%s".', FormException::class)
            );
        }
        $context += $this->defaultContext;
        $exception = $context['exception'];
        $debug = $this->debug && ($context['debug'] ?? true);

        $context['errors'] = $this->extractFormErrors($exception);

        return $this->createResponse($debug, $object, $context);
    }

    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof FlattenException && $context['exception'] instanceof FormException;
    }

    /**
     * @param FormException $exception
     *
     * @return array
     *
     * @author Morteza Karimi <me@morteza-karimi.ir>
     *
     * @since  v1.0
     */
    public function extractFormErrors(FormException $exception): array
    {
        $errors = [];
        foreach ($exception->getErrors() as $error) {
            $cause = $error->getCause();

            if ($cause && $cause->getPropertyPath()) {
                $path = preg_replace('/^((data.)|(.data)|(])|(\\[)|children)/', '', $cause->getPropertyPath());
                $errors[$path][] = $error->getMessage();
            } else {
                $errors[] = $error->getMessage();
            }
        }

        return $errors;
    }
}
