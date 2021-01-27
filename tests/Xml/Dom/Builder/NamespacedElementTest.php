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
use function VeeWee\Xml\Dom\Builder\namespaced_element;
use function VeeWee\Xml\Dom\Builder\value;

class NamespacedElementTest extends TestCase
{
    /** @test */
    public function it_can_build_an_element_with_alias(): void
    {
        $doc = new DOMDocument();
        $ns = 'https://namespace.com';
        /** @var \DOMElement $node */
        $node = namespaced_element($ns, 'ns:hello')($doc);

        self::assertSame($ns, $node->namespaceURI);
        self::assertSame($ns, $node->lookupNamespaceURI('ns'));
        self::assertSame('ns:hello', $node->nodeName);
        self::assertSame('hello', $node->localName);
        self::assertSame($doc, $node->ownerDocument);
    }

    /** @test */
    public function it_can_build_an_element_without_alias(): void
    {
        $doc = new DOMDocument();
        $ns = 'https://namespace.com';
        /** @var \DOMElement $node */
        $node = namespaced_element($ns, 'hello')($doc);

        self::assertSame($ns, $node->namespaceURI);
        self::assertSame('hello', $node->nodeName);
        self::assertSame('hello', $node->localName);
        self::assertSame($doc, $node->ownerDocument);
    }

    /** @test */
    public function it_can_build_an_element_with_configurators(): void
    {
        $doc = new DOMDocument();
        $ns = 'https://namespace.com';
        $node = namespaced_element($ns, 'ns:hello', identity())($doc);

        self::assertSame($ns, $node->lookupNamespaceURI('ns'));
        self::assertSame('ns:hello', $node->nodeName);
        self::assertSame('hello', $node->localName);
        self::assertSame($doc, $node->ownerDocument);
    }

    /** @test */
    public function it_can_build_an_element_with_value(): void
    {
        $doc = new DOMDocument();
        $ns = 'https://namespace.com';
        $node = namespaced_element($ns, 'ns:hello', value('world'))($doc);

        self::assertSame($ns, $node->lookupNamespaceURI('ns'));
        self::assertSame('ns:hello', $node->nodeName);
        self::assertSame('hello', $node->localName);
        self::assertSame($doc, $node->ownerDocument);
    }
}
