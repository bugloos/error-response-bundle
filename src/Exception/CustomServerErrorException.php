<?php

/**
 * This file is part of the bugloos/error-response-bundle project.
 * (c) Bugloos <https://bugloos.com/>
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Bugloos\ErrorResponseBundle\Exception;

use Symfony\Component\HttpFoundation\Response;

/**
 * @author Mojtaba Gheytasi <mjgheytasi@gmail.com>
 */
class CustomServerErrorException extends \RuntimeException implements \Throwable
{
    private int $statusCode;

    public function __construct(string $message = 'Oops! Internal Server Error', int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR, \Throwable $previous = null, ?int $code = 0)
    {
        $this->statusCode = $statusCode;

        $this->message = $message;

        parent::__construct($message, $code, $previous);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
