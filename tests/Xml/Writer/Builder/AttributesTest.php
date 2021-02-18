<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Writer\Builder;

use PHPUnit\Framework\TestCase;
use VeeWee\Tests\Xml\Writer\Helper\UseInMemoryWriterTrait;
use VeeWee\Xml\Writer\Writer;
use XMLWriter;
use function VeeWee\Xml\Writer\Builder\attributes;
use function VeeWee\Xml\Writer\Builder\element;

final class AttributesTest extends TestCase
{
    use UseInMemoryWriterTrait;

    
    public function test_it_can_add_atributes_to_element(): void
    {
        $result = $this->runInMemory(static function (XMLWriter $xmlWriter): void {
            $writer = Writer::fromUnsafeWriter($xmlWriter);
            $writer->write(
                element('hello', attributes([
                    'default' => 'world',
                    'value' => 'Jos',
                ]))
            );
        });

        static::assertXmlStringEqualsXmlString(
            '<hello default="world" value="Jos" />',
            $result
        );
    }
}
