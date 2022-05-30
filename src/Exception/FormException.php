<?php

/**
 * This file is part of the bugloos/error-response-bundle project.
 * (c) Bugloos <https://bugloos.com/>
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Bugloos\ErrorResponseBundle\Exception;

use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @author Mojtaba Gheytasi <mjgheytasi@gmail.com>
 */
class FormException extends HttpException
{
    protected FormInterface $form;

    public function __construct(
        FormInterface $form,
        string $message = 'Invalid Form Data',
        int $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY,
        \Exception $previous = null,
        array $headers = [],
        ?int $code = 0
    ) {
        parent::__construct($statusCode, $message, $previous, $headers, $code);

        $this->form = $form;
    }

    public function getForm(): FormInterface
    {
        return $this->form;
    }

    public function getErrors(): FormErrorIterator
    {
        return $this->form->getErrors(true, true);
    }
}
