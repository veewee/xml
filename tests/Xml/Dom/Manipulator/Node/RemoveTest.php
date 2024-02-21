<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Manipulator\Node;

use \DOM\XMLDocument as DOMDocument;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Locator\document_element;
use function VeeWee\Xml\Dom\Manipulator\Node\remove;
use function VeeWee\Xml\Dom\Manipulator\Node\remove_namespace;

final class RemoveTest extends TestCase
{
    public function test_it_can_remove_a_node_from_a_document(): void
    {
        $doc = Document::fromXmlString('<hello><item /></hello>');
        $node = $doc->xpath()->querySingle('//item');

        $result = remove($node);

        static::assertXmlStringEqualsXmlString($doc->toXmlString(), '<hello/>');
        static::assertSame($result, $node);
    }

    public function test_it_can_remove_a_comment(): void
    {
        $doc = Document::fromXmlString('<hello><!-- hello --></hello>');
        $node = $doc->map(
            static fn (DOMDocument $document) => $document->documentElement->firstChild
        );

        $result = remove($node);

        static::assertXmlStringEqualsXmlString($doc->toXmlString(), '<hello/>');
        static::assertSame($result, $node);
    }

    public function test_it_can_remove_a_text_node(): void
    {
        $doc = Document::fromXmlString('<hello>World</hello>');
        $node = $doc->map(
            static fn (DOMDocument $document) => $document->documentElement->firstChild
        );

        $result = remove($node);

        static::assertXmlStringEqualsXmlString($doc->toXmlString(), '<hello/>');
        static::assertSame($result, $node);
    }

    public function test_it_can_not_remove_an_element(): void
    {
        $this->expectException(RuntimeException::class);

        $doc = Document::fromXmlString('<hello />');
        $node = $doc->toUnsafeDocument();

        remove($node);
    }

    public function test_it_can_remove_attributes(): void
    {
        $doc = Document::fromXmlString('<hello who="world"/>');
        $root = $doc->map(document_element());
        $node = $root->attributes->getNamedItem('who');

        $result = remove($node);

        static::assertXmlStringEqualsXmlString($doc->toXmlString(), '<hello/>');
        static::assertSame($result, $node);
    }

    public function test_it_can_remove_namespaced_attributes(): void
    {
        $doc = Document::fromXmlString('<hello xmlns:who="http://test.com" who:a="world"/>');
        $root = $doc->map(document_element());
        $node = $root->getAttributeNode('who:a');

        $result = remove($node);

        static::assertXmlStringEqualsXmlString($doc->toXmlString(), '<hello xmlns:who="http://test.com" />');
        static::assertSame($result, $node);
    }

    public function test_it_can_remove_xmlns_attributes(): void
    {
        $doc = Document::fromXmlString('<hello xmlns:who="http://world.com"/>');
        $root = $doc->map(document_element());
        $node = $root->getAttributeNode('xmlns:who');

        $result = remove_namespace($node, $root);

        static::assertXmlStringEqualsXmlString($doc->toXmlString(), '<hello/>');
        static::assertSame($result, $node);
    }
}
