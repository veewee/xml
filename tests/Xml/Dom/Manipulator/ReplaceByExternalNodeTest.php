<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Dom\Manipulator;

use DOMElement;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Manipulator\Node\replace_by_external_node;
use PHPUnit\Framework\TestCase;

class ReplaceByExternalNodeTest extends TestCase
{
    /** @test */
    public function it_can_replace_a_node(): void
    {
        $source = new \DOMDocument();
        $source->loadXML('<hello />');
        $target = new \DOMDocument();
        $target->loadXML('<world />');

        $result = replace_by_external_node($target->documentElement, $source->documentElement);

        self::assertInstanceOf(DOMElement::class, $result);
        self::assertSame('hello', $result->nodeName);
        self::assertXmlStringEqualsXmlString($target->saveXML(), $source->saveXML());
    }

    /** @test */
    public function it_can_not_replace_a_document_into_a_document(): void
    {
        $source = new \DOMDocument();
        $source->loadXML('<hello />');
        $target = new \DOMDocument();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Could not replace a node without parent node. (DOMDocument)');
        replace_by_external_node($target, $source);
    }

    /** @test */
    public function it_can_recursively_replace_a_node_with_another_external_node(): void
    {
        $source = new \DOMDocument();
        $source->loadXML('<hello><world><name>VeeWee</name></world></hello>');
        $target = new \DOMDocument();
        $target->loadXML('<hello></hello>');
        $expected = new \DOMDocument();
        $expected->loadXML('<world><name>VeeWee</name></world>');

        $result = replace_by_external_node($target->documentElement, $source->documentElement->firstChild);

        self::assertInstanceOf(DOMElement::class, $result);
        self::assertSame('world', $result->nodeName);
        self::assertXmlStringEqualsXmlString($expected->saveXML(), $target->saveXML());
    }
}
