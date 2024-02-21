<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Manipulator\Node;

use \DOM\XMLDocument;
use \DOM\Element;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Manipulator\Node\import_node_deeply;

final class ImportNodeDeeplyTest extends TestCase
{
    public function test_it_can_import_a_node(): void
    {
        $source = Document::fromXmlString('<hello />')->toUnsafeDocument();
        $target = Document::empty()->toUnsafeDocument();

        $result = import_node_deeply($target, $source->documentElement);

        static::assertInstanceOf(\DOM\Element::class, $result);
        static::assertSame('hello', $result->nodeName);
    }


    public function test_it_can_import_a_node_into_a_nodes_document(): void
    {
        $source = Document::fromXmlString('<hello />')->toUnsafeDocument();
        $target = Document::fromXmlString('<hello></hello>')->toUnsafeDocument();

        $result = import_node_deeply($target->documentElement, $source->documentElement);

        static::assertInstanceOf(\DOM\Element::class, $result);
        static::assertSame('hello', $result->nodeName);
    }


    public function test_it_can_not_import_an_invalid_node(): void
    {
        $source = Document::fromXmlString('<hello />')->toUnsafeDocument();
        $target = Document::empty()->toUnsafeDocument();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Cannot import node: Node Type Not Supported');
        import_node_deeply($target, $source);
    }


    public function test_it_can_recursively_import_a_node_based_on_a_target_document_node(): void
    {
        $source = Document::fromXmlString('<hello><world myattrib="myvalue"><name>VeeWee</name></world></hello>')->toUnsafeDocument();
        $target = Document::empty()->toUnsafeDocument();

        $result = import_node_deeply($target, $source->documentElement->firstChild);

        static::assertInstanceOf(\DOM\Element::class, $result);
        static::assertSame('world', $result->nodeName);
        static::assertSame('myvalue', $result->attributes->getNamedItem('myattrib')->nodeValue);
        static::assertSame('name', $result->firstChild->nodeName);
        static::assertSame('VeeWee', $result->firstChild->nodeValue);
    }
}
