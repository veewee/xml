<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Writer\Builder;

use PHPUnit\Framework\TestCase;
use VeeWee\Tests\Xml\Writer\Helper\UseInMemoryWriterTrait;
use VeeWee\Xml\Writer\Writer;
use XMLWriter;
use function VeeWee\Xml\Writer\Builder\cdata;
use function VeeWee\Xml\Writer\Builder\element;
use function VeeWee\Xml\Writer\Builder\raw;
use function VeeWee\Xml\Writer\Builder\value;

final class CdataTest extends TestCase
{
    use UseInMemoryWriterTrait;

    public function test_it_can_create_empty_cdata(): void
    {
        $result = $this->runInMemory(static function (XMLWriter $xmlWriter): void {
            $writer = Writer::fromUnsafeWriter($xmlWriter);
            $writer->write(cdata());
        });

        static::assertSame('<![CDATA[]]>', $result);
    }

    public function test_it_can_create_cdata_with_unescaped_raw_value(): void
    {
        $result = $this->runInMemory(static function (XMLWriter $xmlWriter): void {
            $writer = Writer::fromUnsafeWriter($xmlWriter);
            $writer->write(
                cdata(raw('<hello>world</hello>'))
            );
        });

        static::assertSame('<![CDATA[<hello>world</hello>]]>', $result);
    }

    public function test_it_can_create_cdata_with_unescaped_text_value(): void
    {
        $result = $this->runInMemory(static function (XMLWriter $xmlWriter): void {
            $writer = Writer::fromUnsafeWriter($xmlWriter);
            $writer->write(
                cdata(value('<hello>world</hello>'))
            );
        });

        static::assertSame('<![CDATA[<hello>world</hello>]]>', $result);
    }

    public function test_it_can_create_cdata_inside_element(): void
    {
        $result = $this->runInMemory(static function (XMLWriter $xmlWriter): void {
            $writer = Writer::fromUnsafeWriter($xmlWriter);
            $writer->write(
                element('root', cdata(value('<hello>world</hello>')))
            );
        });

        static::assertSame('<root><![CDATA[<hello>world</hello>]]></root>', $result);
    }
}
