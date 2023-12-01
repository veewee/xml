<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Writer\Builder;

use PHPUnit\Framework\TestCase;
use VeeWee\Tests\Xml\Writer\Helper\UseInMemoryWriterTrait;
use VeeWee\Xml\Writer\Writer;
use XMLWriter;
use function VeeWee\Xml\Writer\Builder\namespaced_element;
use function VeeWee\Xml\Writer\Builder\value;

final class NamespacedElementTest extends TestCase
{
    use UseInMemoryWriterTrait;


    public function test_it_can_create_self_closing_element(): void
    {
        $result = $this->runInMemory(static function (XMLWriter $xmlWriter): void {
            $writer = Writer::fromUnsafeWriter($xmlWriter);
            $writer->write(
                namespaced_element('http://ns', 'xml', 'root')
            );
        });

        static::assertXmlStringEqualsXmlString('<xml:root xmlns:xml="http://ns" />', $result);
    }


    public function test_it_can_create_element_with_value(): void
    {
        $result = $this->runInMemory(static function (XMLWriter $xmlWriter): void {
            $writer = Writer::fromUnsafeWriter($xmlWriter);
            $writer->write(
                namespaced_element('http://ns', 'xml', 'hello', value('world'))
            );
        });

        static::assertXmlStringEqualsXmlString('<xml:hello xmlns:xml="http://ns">world</xml:hello>', $result);
    }

    public function test_it_can_create_element_without_prefix(): void
    {
        $result = $this->runInMemory(static function (XMLWriter $xmlWriter): void {
            $writer = Writer::fromUnsafeWriter($xmlWriter);
            $writer->write(
                namespaced_element('http://ns', null, 'hello', value('world'))
            );
        });

        static::assertXmlStringEqualsXmlString('<hello xmlns="http://ns">world</hello>', $result);
    }
}
