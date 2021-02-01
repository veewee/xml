<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Dom\Loader;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Loader\xml_node_loader;

class XmlNodeLoaderTest extends TestCase
{
    /** @test */
    public function it_can_load_xml_node(): void
    {
        $source = new DOMDocument();
        $source->loadXML($xml = '<hello />');

        $doc = new DOMDocument();
        $loader = xml_node_loader($source->documentElement);

        $loader($doc);
        self::assertXmlStringEqualsXmlString($xml, $doc->saveXML());
    }

    /** @test */
    public function it_can_not_load_invalid_xml_node(): void
    {
        $source = new DOMDocument();
        $source->loadXML($xml = '<hello />');

        $doc = new DOMDocument();
        $loader = xml_node_loader($source);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Cannot import node: Node Type Not Supported');

        $loader($doc);
    }
}
