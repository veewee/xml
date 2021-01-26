<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\ErrorHandling\Issue;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\ErrorHandling\Issue\Level;

class IssueTest extends TestCase
{
    use UseIssueTrait;

    public function testItHasLoadsOfAccessors(): void
    {
        $error = $this->createIssue(Level::warning());

        static::assertTrue($error->level()->isWarning());
        static::assertSame('file.xml', $error->file());
        static::assertSame('message', $error->message());
        static::assertSame(99, $error->line());
        static::assertSame(1, $error->code());
        static::assertSame(10, $error->column());
        static::assertSame('[WARNING] file.xml: message (1) on line 99,10', $error->toString());
    }
}
