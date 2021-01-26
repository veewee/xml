<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\DOM\Manipulator;

use DOMElement;
use PHPUnit\Framework\TestCase;
use function VeeWee\Xml\Dom\Manipulator\Node\append_external_node;

class AppendExternalNodeTest extends TestCase
{
    /** @test */
    public function it_can_import_a_node_into_a_document_root(): void
    {
        $source = new \DOMDocument();
        $source->loadXML('<hello />');
        $target = new \DOMDocument();

        $result = append_external_node($source->documentElement, $target);

        self::assertInstanceOf(DOMElement::class, $result);
        self::assertSame('hello', $result->nodeName);
        self::assertXmlStringEqualsXmlString($source->saveXML(), $target->saveXML());
    }

    /** @test */
    public function it_can_not_import_a_document_into_a_document(): void
    {
        $source = new \DOMDocument();
        $source->loadXML('<hello />');
        $target = new \DOMDocument();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Cannot import node: Node Type Not Supported');
        append_external_node($source, $target);
    }

    /** @test */
    public function it_can_recursively_import_a_node_into_another_document_node(): void
    {
        $source = new \DOMDocument();
        $source->loadXML('<hello><world><name>VeeWee</name></world></hello>');
        $target = new \DOMDocument();
        $target->loadXML('<hello></hello>');

        $result = append_external_node($source->documentElement->firstChild, $target->documentElement);

        self::assertInstanceOf(DOMElement::class, $result);
        self::assertSame('world', $result->nodeName);
        self::assertXmlStringEqualsXmlString($source->saveXML(), $target->saveXML());
    }
}
