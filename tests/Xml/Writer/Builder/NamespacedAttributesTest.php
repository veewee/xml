<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Writer\Builder;

use PHPUnit\Framework\TestCase;
use VeeWee\Tests\Xml\Writer\Helper\UseInMemoryWriterTrait;
use VeeWee\Xml\Writer\Writer;
use XMLWriter;
use function VeeWee\Xml\Writer\Builder\element;
use function VeeWee\Xml\Writer\Builder\namespaced_attributes;

final class NamespacedAttributesTest extends TestCase
{
    use UseInMemoryWriterTrait;


    public function test_it_can_add_atributes_to_element(): void
    {
        $result = $this->runInMemory(static function (XMLWriter $xmlWriter): void {
            $writer = Writer::fromUnsafeWriter($xmlWriter);
            $writer->write(
                element('hello', namespaced_attributes('http://pfx', [
                    'pfx:default' => 'world',
                    'pfx:value' => 'Jos',
                ]))
            );
        });

        static::assertXmlStringEqualsXmlString(
            '<hello xmlns:pfx="http://pfx" pfx:default="world" pfx:value="Jos" />',
            $result
        );
    }

    public function test_it_can_add_unprefixed_atributes_to_element(): void
    {
        $result = $this->runInMemory(static function (XMLWriter $xmlWriter): void {
            $writer = Writer::fromUnsafeWriter($xmlWriter);
            $writer->write(
                element('hello', namespaced_attributes('http://pfx', [
                    'default' => 'world',
                    'value' => 'Jos',
                ]))
            );
        });

        static::assertXmlStringEqualsXmlString(
            '<hello xmlns="http://pfx" default="world" value="Jos" />',
            $result
        );
    }

    public function test_it_can_add_mixed_atributes_to_element(): void
    {
        $result = $this->runInMemory(static function (XMLWriter $xmlWriter): void {
            $writer = Writer::fromUnsafeWriter($xmlWriter);
            $writer->write(
                element('hello', namespaced_attributes('http://pfx', [
                    'pfx1:a' => 'a',
                    'pfx2:b' => 'b',
                    'c' => 'c',
                ]))
            );
        });

        static::assertXmlStringEqualsXmlString(
            '<hello xmlns="http://pfx" xmlns:pfx1="http://pfx" xmlns:pfx2="http://pfx" c="c" pfx1:a="a" pfx2:b="b"/>',
            $result
        );
    }

}
