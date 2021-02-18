<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Writer\Builder;

use PHPUnit\Framework\TestCase;
use VeeWee\Tests\Xml\Writer\Helper\UseInMemoryWriterTrait;
use VeeWee\Xml\Writer\Writer;
use XMLWriter;
use function VeeWee\Xml\Writer\Builder\prefixed_element;
use function VeeWee\Xml\Writer\Builder\value;

final class PrefixedElementTest extends TestCase
{
    use UseInMemoryWriterTrait;

    
    public function test_it_can_create_self_closing_element(): void
    {
        $result = $this->runInMemory(static function (XMLWriter $xmlWriter): void {
            $writer = Writer::fromUnsafeWriter($xmlWriter);
            $writer->write(
                prefixed_element('xml', 'root')
            );
        });

        static::assertXmlStringEqualsXmlString('<xml:root />', $result);
    }

    
    public function test_it_can_create_element_with_value(): void
    {
        $result = $this->runInMemory(static function (XMLWriter $xmlWriter): void {
            $writer = Writer::fromUnsafeWriter($xmlWriter);
            $writer->write(
                prefixed_element('xml', 'hello', value('world'))
            );
        });

        static::assertXmlStringEqualsXmlString('<xml:hello>world</xml:hello>', $result);
    }
}
