<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\ErrorHandling;

use Exception;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\ErrorHandling;

use function libxml_use_internal_errors;
use function simplexml_load_string;

final class DetectIssuesTest extends TestCase
{
    public function test_it_can_continue_when_no_errors_are_detects(): void
    {
        [$result, $errors] = ErrorHandling\detect_issues(
            static function (): string {
                self::assertTrue(libxml_use_internal_errors());

                return 'ok';
            }
        );

        static::assertTrue($result->isSucceeded());
        static::assertSame('ok', $result->getResult());
        static::assertCount(0, $errors);
    }

    public function test_it_can_detect_xml_errors_inside_callable_and_return_ok(): void
    {
        [$result, $errors] = ErrorHandling\detect_issues(
            static function (): string {
                simplexml_load_string('<notvalidxml');

                return 'ok';
            }
        );

        static::assertTrue($result->isSucceeded());
        static::assertSame('ok', $result->getResult());
        static::assertCount(1, $errors);
    }

    public function test_it_can_detect_xml_errors_inside_callable_and_return_a_failure(): void
    {
        $exception = new Exception('nonono');
        [$result, $errors] = ErrorHandling\detect_issues(
            static function () use ($exception) {
                simplexml_load_string('<notvalidxml');

                throw $exception;
            }
        );

        static::assertTrue($result->isFailed());
        static::assertSame($exception, $result->getThrowable());
        static::assertCount(1, $errors);
    }

    public function test_it_does_not_use_previously_occured_exceptions(): void
    {
        libxml_use_internal_errors(true);
        simplexml_load_string('<notvalidxml');

        [$result, $errors] = ErrorHandling\detect_issues(
            static function (): string {
                simplexml_load_string('<notvalidxml');

                return 'ok';
            }
        );

        static::assertTrue($result->isSucceeded());
        static::assertSame('ok', $result->getResult());
        static::assertCount(1, $errors);
    }

    public function test_it_can_use_internal_xml_errors_during_a_function_call(): void
    {
        libxml_use_internal_errors(false);

        [$result, $errors] = ErrorHandling\detect_issues(
            static function () {
                self::assertTrue(libxml_use_internal_errors());

                return 'ok';
            }
        );

        static::assertFalse(libxml_use_internal_errors());
        static::assertTrue($result->isSucceeded());
        static::assertSame('ok', $result->getResult());
        static::assertCount(0, $errors);
    }
}
