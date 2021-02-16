<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\ErrorHandling;

use Exception;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use VeeWee\Xml\ErrorHandling;

use function libxml_use_internal_errors;
use function simplexml_load_string;

final class DisallowIssuesTest extends TestCase
{
    public function test_it_can_continue_when_no_errors_are_detects(): void
    {
        $result = ErrorHandling\disallow_issues(
            static function (): string {
                self::assertTrue(libxml_use_internal_errors());
                return 'ok';
            }
        );

        static::assertSame('ok', $result);
    }

    public function test_it_can_detect_xml_errors_inside_callable_and_return_ok(): void
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

    public function test_it_can_detect_xml_errors_inside_callable_and_return_a_failure(): void
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
