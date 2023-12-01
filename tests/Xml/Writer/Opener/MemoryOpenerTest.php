<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Writer\Opener;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Writer\Writer;
use XMLWriter;
use function VeeWee\Xml\Writer\Builder\raw;
use function VeeWee\Xml\Writer\Mapper\memory_output;
use function VeeWee\Xml\Writer\Opener\memory_opener;

final class MemoryOpenerTest extends TestCase
{
    public function test_it_can_open_in_memory(): void
    {
        $writer = new XMLWriter();
        memory_opener()($writer);

        $actual = Writer::fromUnsafeWriter($writer)
            ->write(raw('hello'))
            ->map(memory_output());

        static::assertSame('hello', $actual);
    }
}
