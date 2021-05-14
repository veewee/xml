<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Builder;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use function VeeWee\Xml\Dom\Builder\element;
use function VeeWee\Xml\Dom\Builder\xmlns_attribute;
use function VeeWee\Xml\Dom\Builder\xmlns_attributes;

final class XmlnsAttributesTest extends TestCase
{
    public function test_it_can_build_an_element_with_attributes(): void
    {
        $doc = new DOMDocument();

        $node = element(
            'hello',
            xmlns_attribute('ns1', 'http://ns1.com'),
            xmlns_attributes(['ns2' => 'http://ns2.com']),
        )($doc);

        static::assertSame('hello', $node->nodeName);
        static::assertSame('hello', $node->localName);
        static::assertSame($doc, $node->ownerDocument);

        static::assertSame($node->getAttribute('xmlns:ns1'), 'http://ns1.com');
        static::assertSame($node->getAttribute('xmlns:ns2'), 'http://ns2.com');
    }
}
