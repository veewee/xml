<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Dom\Builder;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use function Psl\Fun\identity;
use function VeeWee\Xml\Dom\Builder\element;
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
}
