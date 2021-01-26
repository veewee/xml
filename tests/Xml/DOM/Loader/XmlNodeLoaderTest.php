<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\DOM\Loader;

use DOMDocument;
use PHPUnit\Framework\TestCase;
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

        $result = $loader($doc);
        self::assertTrue($result->getResult());
        self::assertXmlStringEqualsXmlString($xml, $doc->saveXML());
    }

    /** @test */
    public function it_can_not_load_invalid_xml_node(): void
    {
        $source = new DOMDocument();
        $source->loadXML($xml = '<hello />');

        $doc = new DOMDocument();
        $loader = xml_node_loader($source);

        $result = $loader($doc);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot import node: Node Type Not Supported');
        $result->getResult();
    }
}
