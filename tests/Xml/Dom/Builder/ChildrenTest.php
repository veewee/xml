<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Dom\Builder;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use function VeeWee\Xml\Dom\Builder\children;
use function VeeWee\Xml\Dom\Builder\element;

class ChildrenTest extends TestCase
{
    /** @test */
    public function it_can_build_document_children(): void
    {
        $doc = new DOMDocument();
        $actual = children(
            element('world1'),
            element('world2')
        )($doc);

        self::assertSame($doc, $actual);

        $children = $doc->childNodes;
        self::assertSame(2, $children->count());
        self::assertSame('world1', $children->item(0)->nodeName);
        self::assertSame('world2', $children->item(1)->nodeName);
    }

    /** @test */
    public function it_can_build_an_element_with_children(): void
    {
        $doc = new DOMDocument();
        $node = element(
            'hello',
            children(
                element('world1'),
                element('world2')
            )
        )($doc);

        self::assertSame('hello', $node->nodeName);
        self::assertSame('hello', $node->localName);
        self::assertSame($doc, $node->ownerDocument);

        $children = $node->childNodes;
        self::assertSame(2, $children->count());
        self::assertSame('world1', $children->item(0)->nodeName);
        self::assertSame('world2', $children->item(1)->nodeName);
    }
}
