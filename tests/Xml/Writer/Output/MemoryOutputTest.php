<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Writer\Output;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Writer\Writer;
use XMLWriter;
use function VeeWee\Xml\Writer\Builder\raw;
use function VeeWee\Xml\Writer\Mapper\memory_output;
use function VeeWee\Xml\Writer\Opener\memory_opener;

final class MemoryOutputTest extends TestCase
{
    public function test_it_can_open_in_memory(): void
    {
        $writer = new XMLWriter();
        memory_opener()($writer);

        Writer::fromUnsafeWriter($writer)
            ->write(raw('hello'));

        $actual = memory_output()($writer);

        static::assertSame('hello', $actual);
    }
}
