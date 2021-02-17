<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Builder;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use function Psl\Fun\identity;
use function VeeWee\Xml\Dom\Builder\element;
use function VeeWee\Xml\Dom\Builder\value;

final class ElementTest extends TestCase
{
    public function test_it_can_build_an_element(): void
    {
        $doc = new DOMDocument();
        $node = element('hello')($doc);

        static::assertSame('hello', $node->nodeName);
        static::assertSame('hello', $node->localName);
        static::assertSame($doc, $node->ownerDocument);
    }

    public function test_it_can_build_an_element_with_configurators(): void
    {
        $doc = new DOMDocument();
        $node = element('hello', identity())($doc);

        static::assertSame('hello', $node->nodeName);
        static::assertSame('hello', $node->localName);
        static::assertSame($doc, $node->ownerDocument);
    }

    public function test_it_can_build_an_element_with_value(): void
    {
        $doc = new DOMDocument();
        $node = element('hello', value('world'))($doc);

        static::assertSame('hello', $node->nodeName);
        static::assertSame('hello', $node->localName);
        static::assertSame('world', $node->nodeValue);
        static::assertSame($doc, $node->ownerDocument);
    }
}
