<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Writer\Builder;

use PHPUnit\Framework\TestCase;
use VeeWee\Tests\Xml\Writer\Util\UseInMemoryWriterTrait;
use VeeWee\Xml\Exception\RuntimeException;
use VeeWee\Xml\Writer\Writer;
use XMLWriter;
use function VeeWee\Xml\Writer\Builder\attribute;
use function VeeWee\Xml\Writer\Builder\element;

final class AttributeTest extends TestCase
{
    use UseInMemoryWriterTrait;

    
    public function test_it_can_add_atribute_to_element(): void
    {
        $result = $this->runInMemory(static function (XMLWriter $xmlWriter): void {
            $writer = Writer::fromUnsafeWriter($xmlWriter);
            $writer->write(
                element('hello', attribute('value', 'world'))
            );
        });

        static::assertXmlStringEqualsXmlString(
            '<hello value="world" />',
            $result
        );
    }

    
    public function test_it_can_not_write_attribute_to_invalid_context(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectErrorMessage('Could not write the provided XML to the stream.');

        $this->runInMemory(static function (XMLWriter $xmlWriter): void {
            $writer = Writer::fromUnsafeWriter($xmlWriter);
            $writer->write(
                attribute('value', 'world')
            );
        });
    }
}
