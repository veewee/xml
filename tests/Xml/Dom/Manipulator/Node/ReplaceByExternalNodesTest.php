<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Manipulator\Node;

use \DOM\XMLDocument;
use \DOM\Element;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Locator\Element\children;
use function VeeWee\Xml\Dom\Manipulator\Node\replace_by_external_nodes;

final class ReplaceByExternalNodesTest extends TestCase
{
    public function test_it_can_replace_a_node(): void
    {
        $source = Document::fromXmlString('<hello />');
        $target = Document::fromXmlString('<world />');

        $results = replace_by_external_nodes($target->locateDocumentElement(), [$source->locateDocumentElement()]);
        $result = $results[0];

        static::assertInstanceOf(\DOM\Element::class, $result);
        static::assertSame('hello', $result->nodeName);
        static::assertXmlStringEqualsXmlString($target->toXmlString(), $source->toXmlString());
    }

    public function test_it_can_replace_many_nodes(): void
    {
        $source = Document::fromXmlString('<hello><world /><toon /></hello>');
        $target = Document::fromXmlString('<hello><items /></hello>');
        $items = children($source->locateDocumentElement());

        $results = replace_by_external_nodes($target->locateDocumentElement()->childNodes->item(0), $items);

        static::assertInstanceOf(\DOM\Element::class, $results[0]);
        static::assertSame('world', $results[0]->nodeName);
        static::assertInstanceOf(\DOM\Element::class, $results[1]);
        static::assertSame('toon', $results[1]->nodeName);

        static::assertXmlStringEqualsXmlString($target->toXmlString(), $source->toXmlString());
    }

    public function test_it_can_not_replace_a_document_into_a_document(): void
    {
        $source = Document::fromXmlString('<hello />')->toUnsafeDocument();
        $target = Document::empty()->toUnsafeDocument();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Could not replace a node without parent node. (DOM\XMLDocument)');
        replace_by_external_nodes($target, [$source]);
    }


    public function test_it_can_recursively_replace_a_node_with_another_external_node(): void
    {
        $source = Document::fromXmlString('<hello><world><name>VeeWee</name></world></hello>');
        $target = Document::fromXmlString('<hello></hello>');
        $expected = Document::fromXmlString('<world><name>VeeWee</name></world>');

        $results = replace_by_external_nodes($target->locateDocumentElement(), [$source->locateDocumentElement()->firstChild]);
        $result = $results[0];

        static::assertInstanceOf(\DOM\Element::class, $result);
        static::assertSame('world', $result->nodeName);
        static::assertXmlStringEqualsXmlString($expected->toXmlString(), $target->toXmlString());
    }
}
