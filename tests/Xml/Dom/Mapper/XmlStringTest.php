<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Mapper;

use \DOM\Element;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Mapper\xml_string;

final class XmlStringTest extends TestCase
{
    public function test_it_can_map_doc_to_xml(): void
    {
        $doc = Document::fromXmlString($xml = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL.'<root><item/></root>');
        static::assertSame($xml, xml_string()($doc->toUnsafeDocument()));
    }

    public function test_it_can_map_node_to_xml(): void
    {
        $doc = Document::fromXmlString($xml = '<root><item/></root>');
        $node = $doc->toUnsafeDocument()->getElementsByTagName('item')->item(0);

        static::assertSame('<item/>', xml_string()($node));
    }
}
