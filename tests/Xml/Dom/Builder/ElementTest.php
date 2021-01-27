<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Dom\Builder;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use function Psl\Fun\identity;
use function VeeWee\Xml\Dom\Builder\attribute;
use function VeeWee\Xml\Dom\Builder\attributes;
use function VeeWee\Xml\Dom\Builder\children;
use function VeeWee\Xml\Dom\Builder\element;
use function VeeWee\Xml\Dom\Builder\namespaced_attribute;
use function VeeWee\Xml\Dom\Builder\namespaced_attributes;
use function VeeWee\Xml\Dom\Builder\value;

class ElementTest extends TestCase
{
    /** @test */
    public function it_can_build_an_element(): void
    {
        $doc = new DOMDocument();
        $node = element('hello')($doc);

        self::assertSame('hello', $node->nodeName);
        self::assertSame('hello', $node->localName);
        self::assertSame($doc, $node->ownerDocument);
    }

    /** @test */
    public function it_can_build_an_element_with_configurators(): void
    {
        $doc = new DOMDocument();
        $node = element('hello', identity())($doc);

        self::assertSame('hello', $node->nodeName);
        self::assertSame('hello', $node->localName);
        self::assertSame($doc, $node->ownerDocument);
    }

    /** @test */
    public function it_can_build_an_element_with_value(): void
    {
        $doc = new DOMDocument();
        $node = element('hello', value('world'))($doc);

        self::assertSame('hello', $node->nodeName);
        self::assertSame('hello', $node->localName);
        self::assertSame('world', $node->nodeValue);
        self::assertSame($doc, $node->ownerDocument);
    }

    /** @test */
    public function it_can_build_an_element_with_attributes(): void
    {
        $doc = new DOMDocument();

        $ns = 'https://namespace.com';
        $node = element(
            'hello',
            attribute('key1', 'value1'),
            attributes(['key2' => 'value2']),
            namespaced_attribute($ns, 'ns:key1', 'nsvalue1'),
            namespaced_attributes($ns, ['ns:key2' => 'nsvalue2']),
        )($doc);

        self::assertSame('hello', $node->nodeName);
        self::assertSame('hello', $node->localName);
        self::assertSame($doc, $node->ownerDocument);

        self::assertSame($node->getAttribute('key1'), 'value1');
        self::assertSame($node->getAttribute('key2'), 'value2');
        self::assertSame($node->getAttributeNS($ns, 'key1'), 'nsvalue1');
        self::assertSame($node->getAttributeNS($ns, 'key2'), 'nsvalue2');
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
