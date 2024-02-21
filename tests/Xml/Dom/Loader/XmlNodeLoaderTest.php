<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Loader;

use \DOM\XMLDocument;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Loader\xml_node_loader;

final class XmlNodeLoaderTest extends TestCase
{
    public function test_it_can_load_xml_node(): void
    {
        $source = Document::fromXmlString($xml = '<hello />')->toUnsafeDocument();
        $doc = Document::empty()->toUnsafeDocument();

        $loader = xml_node_loader($source->documentElement);

        $loader($doc);
        static::assertXmlStringEqualsXmlString($xml, $doc->saveXML());
    }


    public function test_it_can_not_load_invalid_xml_node(): void
    {
        $source = Document::fromXmlString($xml = '<hello />')->toUnsafeDocument();
        $doc = Document::empty()->toUnsafeDocument();

        $loader = xml_node_loader($source);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Cannot import node: Node Type Not Supported');

        $loader($doc);
    }
}
