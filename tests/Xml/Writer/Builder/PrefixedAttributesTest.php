<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Writer\Builder;

use PHPUnit\Framework\TestCase;
use VeeWee\Tests\Xml\Writer\Helper\UseInMemoryWriterTrait;
use VeeWee\Xml\Exception\RuntimeException;
use VeeWee\Xml\Writer\Writer;
use XMLWriter;
use function VeeWee\Xml\Writer\Builder\element;
use function VeeWee\Xml\Writer\Builder\prefixed_attributes;

final class PrefixedAttributesTest extends TestCase
{
    use UseInMemoryWriterTrait;

    
    public function test_it_can_add_atributes_to_element(): void
    {
        $result = $this->runInMemory(static function (XMLWriter $xmlWriter): void {
            $writer = Writer::fromUnsafeWriter($xmlWriter);
            $writer->write(
                element('hello', prefixed_attributes([
                    'pfx:default' => 'world',
                    'pfx:value' => 'Jos',
                ]))
            );
        });

        static::assertXmlStringEqualsXmlString(
            '<hello pfx:default="world" pfx:value="Jos" />',
            $result
        );
    }

    
    public function test_it_throws_exception_on_non_prefixed_attribute(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectErrorMessage('The provided value "default" is not a QName, expected ns:name instead');

        $this->runInMemory(static function (XMLWriter $xmlWriter): void {
            $writer = Writer::fromUnsafeWriter($xmlWriter);
            $writer->write(
                element('hello', prefixed_attributes([
                    'default' => 'world',
                ]))
            );
        });
    }
}
