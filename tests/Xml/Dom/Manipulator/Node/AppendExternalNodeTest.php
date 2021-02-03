<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Dom\Manipulator\Node;

use DOMDocument;
use DOMElement;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use function VeeWee\Xml\Dom\Manipulator\Node\append_external_node;

final class AppendExternalNodeTest extends TestCase
{
    public function test_it_can_import_a_node_into_a_document_root(): void
    {
        $source = new DOMDocument();
        $source->loadXML('<hello />');
        $target = new DOMDocument();

        $result = append_external_node($target, $source->documentElement);

        static::assertInstanceOf(DOMElement::class, $result);
        static::assertSame('hello', $result->nodeName);
        static::assertXmlStringEqualsXmlString($source->saveXML(), $target->saveXML());
    }

    
    public function test_it_can_not_import_a_document_into_a_document(): void
    {
        $source = new DOMDocument();
        $source->loadXML('<hello />');
        $target = new DOMDocument();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Cannot import node: Node Type Not Supported');
        append_external_node($target, $source);
    }

    
    public function test_it_can_recursively_import_a_node_into_another_document_node(): void
    {
        $source = new DOMDocument();
        $source->loadXML('<hello><world><name>VeeWee</name></world></hello>');
        $target = new DOMDocument();
        $target->loadXML('<hello></hello>');

        $result = append_external_node($target->documentElement, $source->documentElement->firstChild);

        static::assertInstanceOf(DOMElement::class, $result);
        static::assertSame('world', $result->nodeName);
        static::assertXmlStringEqualsXmlString($source->saveXML(), $target->saveXML());
    }
}
