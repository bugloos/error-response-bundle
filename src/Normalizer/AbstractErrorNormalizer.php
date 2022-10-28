<?php

/**
 * This file is part of the bugloos/error-response-bundle project.
 * (c) Bugloos <https://bugloos.com/>
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Bugloos\ErrorResponseBundle\Normalizer;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

abstract class AbstractErrorNormalizer implements NormalizerInterface
{
    public const TITLE = 'title';
    public const STATUS = 'statusCode';
    public const MESSAGE = 'message';
    public const ERRORS = 'errors';

    protected $debug;
    protected $defaultContext = [
        self::TITLE => 'An error occurred',
        self::ERRORS => [],
    ];

    public function __construct(ParameterBagInterface $bag, array $defaultContext = [])
    {
        $this->debug = $bag->get('kernel.debug');
        $this->defaultContext = $defaultContext + $this->defaultContext;
    }

    protected function createResponse(bool $debug, FlattenException $object, array $context = []): array
    {
        $data = [
            self::TITLE => $context['title'],
            self::STATUS => $context['status'] ?? $object->getStatusCode(),
            self::MESSAGE => $debug ? $object->getMessage() : $object->getStatusText(),
            self::ERRORS => $context['errors'],
        ];
        if ($debug) {
            $data['class'] = $object->getClass();
            $data['trace'] = $object->getTrace();
        }

        return $data;
    }
}
