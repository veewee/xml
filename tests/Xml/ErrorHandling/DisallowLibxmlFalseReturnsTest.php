<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\ErrorHandling;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

final class DisallowLibxmlFalseReturnsTest extends TestCase
{
    public function test_it_continues_when_not_false(): void
    {
        $actual = disallow_libxml_false_returns(true, 'nope');
        static::assertSame(true, $actual);
    }

    
    public function test_it_throws_when_false(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('nope');

        disallow_libxml_false_returns(false, 'nope');
    }
}
