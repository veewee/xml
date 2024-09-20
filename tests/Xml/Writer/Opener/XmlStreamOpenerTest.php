<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Writer\Opener;

use PHPUnit\Framework\TestCase;
use VeeWee\Tests\Xml\Helper\FillFileTrait;
use VeeWee\Xml\Exception\RuntimeException;
use VeeWee\Xml\Writer\Writer;
use function VeeWee\Xml\Writer\Applicative\flush;
use function VeeWee\Xml\Writer\Builder\raw;
use function VeeWee\Xml\Writer\Opener\xml_stream_opener;

final class XmlStreamOpenerTest extends TestCase
{
    use FillFileTrait;

    public function test_it_can_open_in_memory(): void
    {
        [$file, $handle] = $this->fillFile('');

        $writer = xml_stream_opener($handle)();
        Writer::fromUnsafeWriter($writer)
            ->write(raw('hello'))
            ->apply(flush());

        rewind($handle);

        static::assertSame('hello', stream_get_contents($handle));
        static::assertSame('hello', file_get_contents($file));
    }

    public function test_it_can_not_open_closed_resource(): void
    {
        [$_, $handle] = $this->fillFile('');
        fclose($handle);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('supplied resource is not a valid stream resource');

        xml_stream_opener($handle)();
    }
}
