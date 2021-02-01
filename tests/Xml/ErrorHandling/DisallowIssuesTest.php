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

        self::assertSame('ok', $result);
    }

    public function testItCanDetectXmlErrorsInsideCallableAndReturnOk(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectErrorMessage('XML issues detected');

        $result = ErrorHandling\disallow_issues(
            static function (): string {
                simplexml_load_string('<notvalidxml');

                return 'ok';
            }
        );
    }

    public function testItCanDetectXmlErrorsInsideCallableAndReturnAFailure(): void
    {
        $exception = new Exception('nonono');

        $this->expectException(RuntimeException::class);
        $this->expectErrorMessage('nonono');

        ErrorHandling\disallow_issues(
            static function () use ($exception) {
                simplexml_load_string('<notvalidxml');

                throw $exception;
            }
        );
    }
}
