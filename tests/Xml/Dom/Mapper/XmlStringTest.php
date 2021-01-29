<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Mapper;

use DOMElement;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Mapper\xml_string;

class XmlStringTest extends TestCase
{
    /** @test */
    public function it_can_map_doc_to_xml(): void
    {
        $doc = Document::fromXmlString($xml = '<?xml version="1.0"?>'.PHP_EOL.'<root><item/></root>'.PHP_EOL);
        self::assertSame($xml, xml_string()($doc->toUnsafeDocument()));
    }

    /** @test */
    public function it_can_map_node_to_xml(): void
    {
        $doc = Document::fromXmlString($xml = '<root><item/></root>');
        $node = $doc->toUnsafeDocument()->getElementsByTagName('item')->item(0);

        self::assertSame('<item/>', xml_string()($node));
    }

    /** @test */
    public function it_throws_exception_on_invalid_node(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectErrorMessage('Expected to find an ownerDocument on provided DOMNode');
        xml_string()(new DOMElement('hello'));
    }
}
