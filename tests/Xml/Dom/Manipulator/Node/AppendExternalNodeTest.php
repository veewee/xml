<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Manipulator\Node;

use \DOM\XMLDocument;
use \DOM\Element;
use Infected\PhpParser\Comment\Doc;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Manipulator\Node\append_external_node;

final class AppendExternalNodeTest extends TestCase
{
    public function test_it_can_import_a_node_into_a_document_root(): void
    {
        $source = Document::fromXmlString('<hello />')->toUnsafeDocument();
        $target = Document::empty()->toUnsafeDocument();

        $result = append_external_node($target, $source->documentElement);

        static::assertInstanceOf(\DOM\Element::class, $result);
        static::assertSame('hello', $result->nodeName);
        static::assertXmlStringEqualsXmlString($source->saveXML(), $target->saveXML());
    }


    public function test_it_can_not_import_a_document_into_a_document(): void
    {
        $source = Document::fromXmlString('<hello />')->toUnsafeDocument();
        $target = Document::empty()->toUnsafeDocument();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Cannot import node: Node Type Not Supported');
        append_external_node($target, $source);
    }


    public function test_it_can_recursively_import_a_node_into_another_document_node(): void
    {
        $source = Document::fromXmlString('<hello><world><name>VeeWee</name></world></hello>')->toUnsafeDocument();
        $target = Document::fromXmlString('<hello></hello>')->toUnsafeDocument();

        $result = append_external_node($target->documentElement, $source->documentElement->firstChild);

        static::assertInstanceOf(\DOM\Element::class, $result);
        static::assertSame('world', $result->nodeName);
        static::assertXmlStringEqualsXmlString($source->saveXML(), $target->saveXML());
    }
}
