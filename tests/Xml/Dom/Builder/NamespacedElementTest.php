<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Builder;

use DOMDocument;
use DOMElement;
use PHPUnit\Framework\TestCase;
use function Psl\Fun\identity;
use function VeeWee\Xml\Dom\Builder\namespaced_element;
use function VeeWee\Xml\Dom\Builder\value;

final class NamespacedElementTest extends TestCase
{
    public function test_it_can_build_an_element_with_alias(): void
    {
        $doc = new DOMDocument();
        $ns = 'https://namespace.com';
        /** @var DOMElement $node */
        $node = namespaced_element($ns, 'ns:hello')($doc);

        static::assertSame($ns, $node->namespaceURI);
        static::assertSame($ns, $node->lookupNamespaceURI('ns'));
        static::assertSame('ns:hello', $node->nodeName);
        static::assertSame('hello', $node->localName);
        static::assertSame($doc, $node->ownerDocument);
    }

    
    public function test_it_can_build_an_element_without_alias(): void
    {
        $doc = new DOMDocument();
        $ns = 'https://namespace.com';
        /** @var DOMElement $node */
        $node = namespaced_element($ns, 'hello')($doc);

        static::assertSame($ns, $node->namespaceURI);
        static::assertSame('hello', $node->nodeName);
        static::assertSame('hello', $node->localName);
        static::assertSame($doc, $node->ownerDocument);
    }

    
    public function test_it_can_build_an_element_with_configurators(): void
    {
        $doc = new DOMDocument();
        $ns = 'https://namespace.com';
        $node = namespaced_element($ns, 'ns:hello', identity())($doc);

        static::assertSame($ns, $node->lookupNamespaceURI('ns'));
        static::assertSame('ns:hello', $node->nodeName);
        static::assertSame('hello', $node->localName);
        static::assertSame($doc, $node->ownerDocument);
    }

    
    public function test_it_can_build_an_element_with_value(): void
    {
        $doc = new DOMDocument();
        $ns = 'https://namespace.com';
        $node = namespaced_element($ns, 'ns:hello', value('world'))($doc);

        static::assertSame($ns, $node->lookupNamespaceURI('ns'));
        static::assertSame('ns:hello', $node->nodeName);
        static::assertSame('hello', $node->localName);
        static::assertSame($doc, $node->ownerDocument);
    }
}
