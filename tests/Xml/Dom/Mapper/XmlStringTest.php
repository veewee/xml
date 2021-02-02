<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Mapper;

use DOMElement;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Mapper\xml_string;

final class XmlStringTest extends TestCase
{
    public function testIt_can_map_doc_to_xml(): void
    {
        $doc = Document::fromXmlString($xml = '<?xml version="1.0"?>'.PHP_EOL.'<root><item/></root>'.PHP_EOL);
        static::assertSame($xml, xml_string()($doc->toUnsafeDocument()));
    }

    
    public function testIt_can_map_node_to_xml(): void
    {
        $doc = Document::fromXmlString($xml = '<root><item/></root>');
        $node = $doc->toUnsafeDocument()->getElementsByTagName('item')->item(0);

        static::assertSame('<item/>', xml_string()($node));
    }

    
    public function testIt_throws_exception_on_invalid_node(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectErrorMessage('Expected to find an ownerDocument on provided DOMNode');
        xml_string()(new DOMElement('hello'));
    }
}
