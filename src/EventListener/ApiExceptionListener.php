<?php

/**
 * This file is part of the bugloos/error-response-bundle project.
 * (c) Bugloos <https://bugloos.com/>
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Bugloos\ErrorResponseBundle\EventListener;

use Bugloos\ErrorResponseBundle\Exception\CustomServerErrorException;
use Bugloos\ErrorResponseBundle\Exception\FormException;
use Bugloos\ErrorResponseBundle\Exception\RequestValidatorException;
use Bugloos\ErrorResponseBundle\Factory\NormalizerFactory;
use Bugloos\ErrorResponseBundle\Response\ErrorResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

/**
 * @author Mojtaba Gheytasi <mjgheytasi@gmail.com>
 */
final class ApiExceptionListener
{
    private NormalizerFactory $normalizerFactory;
    private LoggerInterface $logger;
    private string $env;

    public function __construct(
        NormalizerFactory $normalizerFactory,
        LoggerInterface $logger,
        string $env
    ) {
        $this->normalizerFactory = $normalizerFactory;
        $this->logger = $logger;
        $this->env = $env;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $request = $event->getRequest();

        if (
            ! \in_array('application/json', $request->getAcceptableContentTypes()) &&
            ! $this->appIsInProdMode()
        ) {
            return;
        }

        $statusCode = $exception instanceof HttpExceptionInterface ?
            $exception->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR;

        if ($exception instanceof \RuntimeException) {
            $statusCode = $exception->getCode();
        }

        if (Response::HTTP_INTERNAL_SERVER_ERROR === $statusCode) {
            $this->logger->error($exception->getMessage(), ['exception'=>$exception]);

            $response = $this->createInternalErrorResponse($statusCode, $exception);

            $event->setResponse($response);

            return;
        }

        $response = $this->createBadRequestErrorResponse($statusCode, $exception);

        $event->setResponse($response);
    }

    private function createInternalErrorResponse(int $statusCode, $exception): ErrorResponse
    {
        $message = $exception instanceof CustomServerErrorException ?
            $exception->getMessage() : 'Oops! Internal Server Error';

        return new ErrorResponse($statusCode, $message);
    }

    private function createBadRequestErrorResponse(
        int $statusCode,
        $exception
    ): ErrorResponse {
        $errors = [];

        if ($this->exceptionHasErrorDetails($exception)) {
            $normalizer = $this->normalizerFactory->getNormalizer($exception);

            try {
                $errors = $normalizer ? $normalizer->normalize($exception) : $exception->getErrors();
            } catch (ExceptionInterface $e) {
                $errors = $exception->getErrors();
            }
        }

        return new ErrorResponse($statusCode, $exception->getMessage(), $errors);
    }

    private function exceptionHasErrorDetails($exception): bool
    {
        return $exception instanceof FormException
            || $exception instanceof RequestValidatorException;
    }

    private function appIsInProdMode(): bool
    {
        return $this->env === 'prod';
    }
}

