<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\ErrorHandling;

use LibXMLError;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\ErrorHandling\Issue\Level;

use function VeeWee\Xml\ErrorHandling\issue_from_xml_error;

final class IssueFromXmlErrorTest extends TestCase
{
    public function test_it_can_construct_issue_from_lib_xml_error(): void
    {
        $error = new LibXMLError();
        $error->level   = Level::error()->value();
        $error->file     = 'file.xml';
        $error->message = 'message';
        $error->line    = 99;
        $error->code    = 1;
        $error->column  = 10;

        $issue = issue_from_xml_error($error);

        static::assertSame($error->level, $issue->level()->value());
        static::assertSame($error->file, $issue->file());
        static::assertSame($error->message, $issue->message());
        static::assertSame($error->line, $issue->line());
        static::assertSame($error->code, $issue->code());
        static::assertSame($error->column, $issue->column());
    }

    public function test_it_returns_null_on_invalid_level(): void
    {
        $error = new LibXMLError();
        $error->level = 9000;
        $issue = issue_from_xml_error($error);

        static::assertNull($issue);
    }
}
