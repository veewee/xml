<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\ErrorHandling;

use LibXMLError;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\ErrorHandling\Issue\Level;

use function VeeWee\Xml\ErrorHandling\issue_level_from_xml_error;

final class IssueLevelFromXmlErrorTest extends TestCase
{
    /**
     * @dataProvider provideErrors
     */
    public function test_it_can_construct_level_from_lib_xml_error(int $code, ?Level $expected): void
    {
        $error = new LibXMLError();
        $error->level = $code;

        $actual = issue_level_from_xml_error($error);
        if (!$expected) {
            static::assertNull($actual);
            return;
        }

        static::assertTrue($expected->matches($actual));
    }

    public function provideErrors()
    {
        yield 'error' => [
            LIBXML_ERR_ERROR,
            Level::error()
        ];
        yield 'fatal' => [
            LIBXML_ERR_FATAL,
            Level::fatal()
        ];
        yield 'warning' => [
            LIBXML_ERR_WARNING,
            Level::warning()
        ];
        yield 'unknown' => [
            90000,
            null
        ];
    }
}
