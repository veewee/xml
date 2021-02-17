<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Writer\Builder;

use PHPUnit\Framework\TestCase;
use VeeWee\Tests\Xml\Writer\Helper\UseInMemoryWriterTrait;
use VeeWee\Xml\Writer\Writer;
use XMLWriter;
use function VeeWee\Xml\Writer\Builder\element;
use function VeeWee\Xml\Writer\Builder\namespace_attribute;

final class NamespaceAttributeTest extends TestCase
{
    use UseInMemoryWriterTrait;

    
    public function test_it_can_create_namespace_attribute_without_prefix(): void
    {
        $result = $this->runInMemory(static function (XMLWriter $xmlWriter): void {
            $writer = Writer::fromUnsafeWriter($xmlWriter);
            $writer->write(
                element('root', namespace_attribute('https://awesome.xom'))
            );
        });

        static::assertXmlStringEqualsXmlString('<root xmlns="https://awesome.xom" />', $result);
    }

    
    public function test_it_can_create_namespace_attribute_with_prefix(): void
    {
        $result = $this->runInMemory(static function (XMLWriter $xmlWriter): void {
            $writer = Writer::fromUnsafeWriter($xmlWriter);
            $writer->write(
                element('root', namespace_attribute('https://awesome.xom', 'xml'))
            );
        });

        static::assertXmlStringEqualsXmlString('<root xmlns:xml="https://awesome.xom" />', $result);
    }
}
