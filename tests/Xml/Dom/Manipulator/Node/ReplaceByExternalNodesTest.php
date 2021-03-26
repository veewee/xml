<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Manipulator\Node;

use DOMDocument;
use DOMElement;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Locator\Node\children;
use function VeeWee\Xml\Dom\Manipulator\Node\replace_by_external_nodes;

final class ReplaceByExternalNodesTest extends TestCase
{
    public function test_it_can_replace_a_node(): void
    {
        $source = new DOMDocument();
        $source->loadXML('<hello />');
        $target = new DOMDocument();
        $target->loadXML('<world />');

        $results = replace_by_external_nodes($target->documentElement, [$source->documentElement]);
        $result = $results[0];

        static::assertInstanceOf(DOMElement::class, $result);
        static::assertSame('hello', $result->nodeName);
        static::assertXmlStringEqualsXmlString($target->saveXML(), $source->saveXML());
    }

    public function test_it_can_replace_many_nodes(): void
    {
        $source = new DOMDocument();
        $source->loadXML('<hello><world /><toon /></hello>');
        $target = new DOMDocument();
        $target->loadXML('<hello><items /></hello>');
        $items = children($source->documentElement);

        $results = replace_by_external_nodes($target->documentElement->childNodes->item(0), $items);

        static::assertInstanceOf(DOMElement::class, $results[0]);
        static::assertSame('world', $results[0]->nodeName);
        static::assertInstanceOf(DOMElement::class, $results[1]);
        static::assertSame('toon', $results[1]->nodeName);

        static::assertXmlStringEqualsXmlString($target->saveXML(), $source->saveXML());
    }

    public function test_it_can_not_replace_a_document_into_a_document(): void
    {
        $source = new DOMDocument();
        $source->loadXML('<hello />');
        $target = new DOMDocument();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Could not replace a node without parent node. (DOMDocument)');
        replace_by_external_nodes($target, [$source]);
    }

    
    public function test_it_can_recursively_replace_a_node_with_another_external_node(): void
    {
        $source = new DOMDocument();
        $source->loadXML('<hello><world><name>VeeWee</name></world></hello>');
        $target = new DOMDocument();
        $target->loadXML('<hello></hello>');
        $expected = new DOMDocument();
        $expected->loadXML('<world><name>VeeWee</name></world>');

        $results = replace_by_external_nodes($target->documentElement, [$source->documentElement->firstChild]);
        $result = $results[0];

        static::assertInstanceOf(DOMElement::class, $result);
        static::assertSame('world', $result->nodeName);
        static::assertXmlStringEqualsXmlString($expected->saveXML(), $target->saveXML());
    }
}
