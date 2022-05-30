<?php

/**
 * This file is part of the bugloos/error-response-bundle project.
 * (c) Bugloos <https://bugloos.com/>
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Bugloos\ErrorResponseBundle\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @author Mojtaba Gheytasi <mjgheytasi@gmail.com>
 */
class ErrorResponse extends JsonResponse
{
    public function __construct(
        int $status,
        string $message,
        array $errors = [],
        array $headers = ['Content-Type' => 'application/json'],
        bool $json = false
    ) {
        parent::__construct(
            $this->format($status, $message, $errors),
            $status,
            $headers,
            $json
        );
    }

    private function format(int $statusCode, string $message, array $errors = []): array
    {
        return [
            'statusCode' => $statusCode,
            'message' => $message,
            'errors' => $errors,
        ];
    }
}
