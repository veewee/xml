<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Writer\Builder;

use PHPUnit\Framework\TestCase;
use VeeWee\Tests\Xml\Writer\Helper\UseInMemoryWriterTrait;
use VeeWee\Xml\Exception\RuntimeException;
use VeeWee\Xml\Writer\Writer;
use XMLWriter;
use function VeeWee\Xml\Writer\Builder\attribute;
use function VeeWee\Xml\Writer\Builder\children;
use function VeeWee\Xml\Writer\Builder\element;
use function VeeWee\Xml\Writer\Builder\value;

final class ChildrenTest extends TestCase
{
    use UseInMemoryWriterTrait;

    
    public function test_it_can_create_child_elements(): void
    {
        $result = $this->runInMemory(static function (XMLWriter $xmlWriter): void {
            $writer = Writer::fromUnsafeWriter($xmlWriter);
            $writer->write(
                element('hello', children([
                    attribute('default', 'world'),
                    element('item', value('Jos')),
                    element('item', value('Bos')),
                    element('item', value('Mos')),
                ]))
            );
        });

        static::assertXmlStringEqualsXmlString(
            <<<EOXML
            <hello default="world">
                <item>Jos</item>
                <item>Bos</item>
                <item>Mos</item>
            </hello>
            EOXML,
            $result
        );
    }

    
    public function test_it_can_not_write_attributes_in_between_elements(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Could not write the provided XML to the stream.');

        $this->runInMemory(static function (XMLWriter $xmlWriter): void {
            $writer = Writer::fromUnsafeWriter($xmlWriter);
            $writer->write(
                element('hello', children([
                    element('item', value('Jos')),
                    attribute('default', 'world'),
                ]))
            );
        });
    }
}
