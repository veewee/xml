<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Writer\Builder;

use PHPUnit\Framework\TestCase;
use VeeWee\Tests\Xml\Writer\Helper\UseInMemoryWriterTrait;
use VeeWee\Xml\Writer\Writer;
use XMLWriter;
use function VeeWee\Xml\Writer\Builder\element;
use function VeeWee\Xml\Writer\Builder\raw;

final class RawTest extends TestCase
{
    use UseInMemoryWriterTrait;

    public function test_it_can_create_element_with_value(): void
    {
        $result = $this->runInMemory(static function (XMLWriter $xmlWriter): void {
            $writer = Writer::fromUnsafeWriter($xmlWriter);
            $writer->write(
                element('hello', raw('<to>world</to>'))
            );
        });

        static::assertXmlStringEqualsXmlString('<hello><to>world</to></hello>', $result);
    }

    public function test_it_can_be_used_stand_alone(): void
    {
        $result = $this->runInMemory(static function (XMLWriter $xmlWriter): void {
            $writer = Writer::fromUnsafeWriter($xmlWriter);
            $writer->write(raw('<hello>world</hello>'));
        });

        static::assertSame('<hello>world</hello>', $result);
    }

    public function test_it_does_no_validations(): void
    {
        $result = $this->runInMemory(static function (XMLWriter $xmlWriter): void {
            $writer = Writer::fromUnsafeWriter($xmlWriter);
            $writer->write(raw('<hel/lo>world</hello>'));
        });

        static::assertSame('<hel/lo>world</hello>', $result);
    }

    public function test_it_doesnt_have_to_be_xml(): void
    {
        $result = $this->runInMemory(static function (XMLWriter $xmlWriter): void {
            $writer = Writer::fromUnsafeWriter($xmlWriter);
            $writer->write(raw('{"hello", "world"}'));
        });

        static::assertSame('{"hello", "world"}', $result);
    }
}
