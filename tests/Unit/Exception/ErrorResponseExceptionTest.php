<?php

/**
 * This file is part of the bugloos/error-response-bundle project.
 * (c) Bugloos <https://bugloos.com/>
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Bugloos\ErrorResponseBundle\Tests\Unit\Exception;

use Bugloos\ErrorResponseBundle\Exception\CustomServerErrorException;
use PHPUnit\Framework\TestCase;

/**
 * @author Mojtaba Gheytasi <mjgheytasi@gmail.com>
 */
class ErrorResponseExceptionTest extends TestCase
{
    public function exceptionDataProvider(): \Generator
    {
        yield [
            new CustomServerErrorException(),
            'Oops! Internal Server Error',
        ];
        yield [
            new CustomServerErrorException('Something was wrong'),
            'Something was wrong',
        ];
    }

    /**
     * @dataProvider exceptionDataProvider
     */
    public function test_message_is_right(\RuntimeException $exception, string $message): void
    {
        self::assertSame($message, $exception->getMessage());
    }
}
