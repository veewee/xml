<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Builder;

use \DOM\XMLDocument;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Builder\attribute;
use function VeeWee\Xml\Dom\Builder\attributes;
use function VeeWee\Xml\Dom\Builder\element;
use function VeeWee\Xml\Dom\Builder\namespaced_attribute;
use function VeeWee\Xml\Dom\Builder\namespaced_attributes;

final class AttributesTest extends TestCase
{
    public function test_it_can_build_an_element_with_attributes(): void
    {
        $doc = Document::empty()->toUnsafeDocument();

        $ns = 'https://namespace.com';
        $node = element(
            'hello',
            attribute('key1', 'value1'),
            attributes(['key2' => 'value2']),
            namespaced_attribute($ns, 'ns:key1', 'nsvalue1'),
            namespaced_attributes($ns, ['ns:key2' => 'nsvalue2']),
        )($doc);

        static::assertSame('hello', $node->nodeName);
        static::assertSame('hello', $node->localName);
        static::assertSame($doc, $node->ownerDocument);

        static::assertSame($node->getAttribute('key1'), 'value1');
        static::assertSame($node->getAttribute('key2'), 'value2');
        static::assertSame($node->getAttributeNS($ns, 'key1'), 'nsvalue1');
        static::assertSame($node->getAttributeNS($ns, 'key2'), 'nsvalue2');
    }
}
