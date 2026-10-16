<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\ErrorHandling;

use Error;
use Exception;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use VeeWee\Xml\ErrorHandling;
use VeeWee\Xml\Exception\RuntimeException as XmlRuntimeException;
use function libxml_get_errors;
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
        $this->expectExceptionMessage('XML issues detected');

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
        $this->expectExceptionMessage('nonono');

        ErrorHandling\disallow_issues(
            static function () use ($exception) {
                simplexml_load_string('<notvalidxml');

                throw $exception;
            }
        );
    }

    public function test_it_adds_the_detected_issues_to_the_message(): void
    {
        try {
            ErrorHandling\disallow_issues(
                static function (): string {
                    simplexml_load_string('<notvalidxml');

                    return 'ok';
                }
            );
            static::fail('Expected an exception');
        } catch (XmlRuntimeException $exception) {
            static::assertStringStartsWith('XML issues detected: ' . PHP_EOL . '[FATAL] ', $exception->getMessage());
            static::assertNull($exception->getPrevious());
        }
    }

    public function test_it_wraps_a_failure_with_its_code_and_previous_exception(): void
    {
        $exception = new Exception('nonono', 42);

        try {
            ErrorHandling\disallow_issues(
                static function () use ($exception) {
                    throw $exception;
                }
            );
            static::fail('Expected an exception');
        } catch (XmlRuntimeException $actual) {
            static::assertSame('nonono' . PHP_EOL, $actual->getMessage());
            static::assertSame(42, $actual->getCode());
            static::assertSame($exception, $actual->getPrevious());
        }
    }

    public function test_it_wraps_errors_that_are_not_exceptions(): void
    {
        $error = new Error('broken');

        try {
            ErrorHandling\disallow_issues(
                static function () use ($error) {
                    throw $error;
                }
            );
            static::fail('Expected an exception');
        } catch (XmlRuntimeException $actual) {
            static::assertSame('broken' . PHP_EOL, $actual->getMessage());
            static::assertSame($error, $actual->getPrevious());
        }
    }

    public function test_it_does_not_use_previously_occured_errors(): void
    {
        libxml_use_internal_errors(true);
        simplexml_load_string('<notvalidxml');

        $result = ErrorHandling\disallow_issues(static fn (): string => 'ok');

        static::assertSame('ok', $result);
    }

    public function test_it_restores_the_libxml_state_on_success(): void
    {
        libxml_use_internal_errors(false);

        ErrorHandling\disallow_issues(static fn (): string => 'ok');

        static::assertFalse(libxml_use_internal_errors());
        static::assertSame([], libxml_get_errors());
    }

    public function test_it_restores_the_libxml_state_on_detected_issues(): void
    {
        libxml_use_internal_errors(false);

        try {
            ErrorHandling\disallow_issues(
                static function (): string {
                    simplexml_load_string('<notvalidxml');

                    return 'ok';
                }
            );
            static::fail('Expected an exception');
        } catch (XmlRuntimeException) {
        }

        static::assertFalse(libxml_use_internal_errors());
        static::assertSame([], libxml_get_errors());
    }

    public function test_it_restores_the_libxml_state_on_failure(): void
    {
        libxml_use_internal_errors(false);

        try {
            ErrorHandling\disallow_issues(
                static function (): string {
                    simplexml_load_string('<notvalidxml');

                    throw new Exception('nonono');
                }
            );
            static::fail('Expected an exception');
        } catch (XmlRuntimeException $exception) {
            static::assertStringStartsWith('nonono' . PHP_EOL . '[FATAL] ', $exception->getMessage());
        }

        static::assertFalse(libxml_use_internal_errors());
        static::assertSame([], libxml_get_errors());
    }
}
