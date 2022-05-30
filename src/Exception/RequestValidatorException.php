<?php

/**
 * This file is part of the bugloos/error-response-bundle project.
 * (c) Bugloos <https://bugloos.com/>
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Bugloos\ErrorResponseBundle\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @author Mojtaba Gheytasi <mjgheytasi@gmail.com>
 */
class RequestValidatorException extends HttpException
{
    protected array $errors;

    public function __construct(
        array $errors,
        string $message = 'Invalid Data',
        int $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY,
        \Exception $previous = null,
        array $headers = [],
        ?int $code = 0
    ) {
        parent::__construct($statusCode, $message, $previous, $headers, $code);

        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
