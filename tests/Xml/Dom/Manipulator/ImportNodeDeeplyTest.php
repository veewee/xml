<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Dom\Manipulator;

use DOMDocument;
use DOMElement;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Manipulator\Node\import_node_deeply;

final class ImportNodeDeeplyTest extends TestCase
{
    public function testIt_can_import_a_node(): void
    {
        $source = new DOMDocument();
        $source->loadXML('<hello />');
        $target = new DOMDocument();

        $result = import_node_deeply($target, $source->documentElement);

        static::assertInstanceOf(DOMElement::class, $result);
        static::assertSame('hello', $result->nodeName);
    }

    
    public function testIt_can_import_a_node_into_a_nodes_document(): void
    {
        $source = new DOMDocument();
        $source->loadXML('<hello />');
        $target = new DOMDocument();
        $target->loadXML('<hello></hello>');

        $result = import_node_deeply($target->documentElement, $source->documentElement);

        static::assertInstanceOf(DOMElement::class, $result);
        static::assertSame('hello', $result->nodeName);
    }

    
    public function testIt_can_not_import_an_invalid_node(): void
    {
        $source = new DOMDocument();
        $source->loadXML('<hello />');
        $target = new DOMDocument();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Cannot import node: Node Type Not Supported');
        import_node_deeply($target, $source);
    }

    
    public function testIt_can_recursively_import_a_node_based_on_a_target_document_node(): void
    {
        $source = new DOMDocument();
        $source->loadXML('<hello><world myattrib="myvalue"><name>VeeWee</name></world></hello>');
        $target = new DOMDocument();

        $result = import_node_deeply($target, $source->documentElement->firstChild);

        static::assertInstanceOf(DOMElement::class, $result);
        static::assertSame('world', $result->nodeName);
        static::assertSame('myvalue', $result->attributes->getNamedItem('myattrib')->nodeValue);
        static::assertSame('name', $result->firstChild->nodeName);
        static::assertSame('VeeWee', $result->firstChild->nodeValue);
    }
}
