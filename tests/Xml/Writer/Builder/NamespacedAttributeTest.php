<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Writer\Builder;

use PHPUnit\Framework\TestCase;
use VeeWee\Tests\Xml\Writer\Helper\UseInMemoryWriterTrait;
use VeeWee\Xml\Exception\RuntimeException;
use VeeWee\Xml\Writer\Writer;
use XMLWriter;
use function VeeWee\Xml\Writer\Builder\element;
use function VeeWee\Xml\Writer\Builder\namespaced_attribute;

final class NamespacedAttributeTest extends TestCase
{
    use UseInMemoryWriterTrait;


    public function test_it_can_add_atribute_to_element(): void
    {
        $result = $this->runInMemory(static function (XMLWriter $xmlWriter): void {
            $writer = Writer::fromUnsafeWriter($xmlWriter);
            $writer->write(
                element('hello', namespaced_attribute('http://pfx', 'pfx', 'value', 'world'))
            );
        });

        static::assertXmlStringEqualsXmlString(
            '<hello xmlns:pfx="http://pfx" pfx:value="world" />',
            $result
        );
    }

    public function test_it_can_add_atribute_without_prefix_to_element(): void
    {
        $result = $this->runInMemory(static function (XMLWriter $xmlWriter): void {
            $writer = Writer::fromUnsafeWriter($xmlWriter);
            $writer->write(
                element('hello', namespaced_attribute('http://pfx', null, 'value', 'world'))
            );
        });

        static::assertXmlStringEqualsXmlString(
            '<hello xmlns="http://pfx" value="world" />',
            $result
        );
    }

    public function test_it_can_not_write_attribute_to_invalid_context(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Could not write the provided XML to the stream.');

        $this->runInMemory(static function (XMLWriter $xmlWriter): void {
            $writer = Writer::fromUnsafeWriter($xmlWriter);
            $writer->write(
                namespaced_attribute('http://pfx', 'pfx', 'value', 'world')
            );
        });
    }
}
