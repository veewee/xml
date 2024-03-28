<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Manipulator\Node;

use \DOM\XMLDocument;
use \DOM\Element;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Manipulator\Node\replace_by_external_node;

final class ReplaceByExternalNodeTest extends TestCase
{
    public function test_it_can_replace_a_node(): void
    {
        $source = Document::fromXmlString('<hello />')->toUnsafeDocument();
        $target = Document::fromXmlString('<world />')->toUnsafeDocument();

        $result = replace_by_external_node($target->documentElement, $source->documentElement);

        static::assertInstanceOf(\DOM\Element::class, $result);
        static::assertSame('hello', $result->nodeName);
        static::assertXmlStringEqualsXmlString($target->saveXML(), $source->saveXML());
    }


    public function test_it_can_not_replace_a_document_into_a_document(): void
    {
        $source = Document::fromXmlString('<hello />')->toUnsafeDocument();
        $target = Document::empty()->toUnsafeDocument();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Could not replace a node without parent node. (DOM\XMLDocument)');
        replace_by_external_node($target, $source);
    }


    public function test_it_can_recursively_replace_a_node_with_another_external_node(): void
    {
        $source = Document::fromXmlString('<hello><world><name>VeeWee</name></world></hello>')->toUnsafeDocument();
        $target = Document::fromXmlString('<hello></hello>')->toUnsafeDocument();
        $expected = Document::fromXmlString('<world><name>VeeWee</name></world>')->toUnsafeDocument();

        $result = replace_by_external_node($target->documentElement, $source->documentElement->firstChild);

        static::assertInstanceOf(\DOM\Element::class, $result);
        static::assertSame('world', $result->nodeName);
        static::assertXmlStringEqualsXmlString($expected->saveXML(), $target->saveXML());
    }
}
