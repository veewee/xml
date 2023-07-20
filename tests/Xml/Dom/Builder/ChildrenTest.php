<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Builder;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use function VeeWee\Xml\Dom\Builder\cdata;
use function VeeWee\Xml\Dom\Builder\children;
use function VeeWee\Xml\Dom\Builder\element;
use function VeeWee\Xml\Dom\Mapper\xml_string;

final class ChildrenTest extends TestCase
{
    public function test_it_can_build_document_children(): void
    {
        $doc = new DOMDocument();
        $actual = children(
            element('world1'),
            element('world2')
        )($doc);

        static::assertSame($doc, $actual);

        $children = $doc->childNodes;
        static::assertSame(2, $children->count());
        static::assertSame('world1', $children->item(0)->nodeName);
        static::assertSame('world2', $children->item(1)->nodeName);
    }

    public function test_it_can_build_an_element_with_children(): void
    {
        $doc = new DOMDocument();
        $node = element(
            'hello',
            children(
                element('world1'),
                element('world2')
            )
        )($doc);

        static::assertSame('hello', $node->nodeName);
        static::assertSame('hello', $node->localName);
        static::assertSame($doc, $node->ownerDocument);

        $children = $node->childNodes;
        static::assertSame(2, $children->count());
        static::assertSame('world1', $children->item(0)->nodeName);
        static::assertSame('world2', $children->item(1)->nodeName);
    }

    public function test_it_can_add_cdata(): void
    {
        $doc = new DOMDocument();
        $node = element(
            'hello',
            children(
                cdata('<html>world</html>'),
            )
        )($doc);

        static::assertXmlStringEqualsXmlString(
            '<hello><![CDATA[<html>world</html>]]></hello>',
            xml_string()($node)
        );
    }
}
