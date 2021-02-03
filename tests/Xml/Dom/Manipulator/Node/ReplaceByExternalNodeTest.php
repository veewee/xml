<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Dom\Manipulator\Node;

use DOMDocument;
use DOMElement;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Manipulator\Node\replace_by_external_node;

final class ReplaceByExternalNodeTest extends TestCase
{
    public function test_it_can_replace_a_node(): void
    {
        $source = new DOMDocument();
        $source->loadXML('<hello />');
        $target = new DOMDocument();
        $target->loadXML('<world />');

        $result = replace_by_external_node($target->documentElement, $source->documentElement);

        static::assertInstanceOf(DOMElement::class, $result);
        static::assertSame('hello', $result->nodeName);
        static::assertXmlStringEqualsXmlString($target->saveXML(), $source->saveXML());
    }

    
    public function test_it_can_not_replace_a_document_into_a_document(): void
    {
        $source = new DOMDocument();
        $source->loadXML('<hello />');
        $target = new DOMDocument();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Could not replace a node without parent node. (DOMDocument)');
        replace_by_external_node($target, $source);
    }

    
    public function test_it_can_recursively_replace_a_node_with_another_external_node(): void
    {
        $source = new DOMDocument();
        $source->loadXML('<hello><world><name>VeeWee</name></world></hello>');
        $target = new DOMDocument();
        $target->loadXML('<hello></hello>');
        $expected = new DOMDocument();
        $expected->loadXML('<world><name>VeeWee</name></world>');

        $result = replace_by_external_node($target->documentElement, $source->documentElement->firstChild);

        static::assertInstanceOf(DOMElement::class, $result);
        static::assertSame('world', $result->nodeName);
        static::assertXmlStringEqualsXmlString($expected->saveXML(), $target->saveXML());
    }
}
