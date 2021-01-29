<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\ErrorHandling;

use Exception;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use VeeWee\Xml\ErrorHandling;

use function libxml_use_internal_errors;
use function simplexml_load_string;

final class DisallowIssuesTest extends TestCase
{
    public function testItCanContinueWhenNoErrorsAreDetects(): void
    {
        $result = ErrorHandling\disallow_issues(
            static function (): string {
                self::assertTrue(libxml_use_internal_errors());
                return 'ok';
            }
        );

        static::assertTrue($result->isSucceeded());
        static::assertSame('ok', $result->getResult());
    }

    public function testItCanDetectXmlErrorsInsideCallableAndReturnOk(): void
    {
        $result = ErrorHandling\disallow_issues(
            static function (): string {
                simplexml_load_string('<notvalidxml');

                return 'ok';
            }
        );

        static::assertTrue($result->isFailed());

        $this->expectException(RuntimeException::class);
        $this->expectErrorMessage('XML issues detected');
        $result->getResult();
    }

    public function testItCanDetectXmlErrorsInsideCallableAndReturnAFailure(): void
    {
        $exception = new Exception('nonono');
        $result = ErrorHandling\disallow_issues(
            static function () use ($exception) {
                simplexml_load_string('<notvalidxml');

                throw $exception;
            }
        );

        static::assertTrue($result->isFailed());

        $this->expectException(RuntimeException::class);
        $this->expectErrorMessage('nonono');
        $result->getResult();
    }
}
