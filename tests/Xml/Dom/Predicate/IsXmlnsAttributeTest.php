<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Predicate;

use DOMNode;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Predicate\is_xmlns_attribute;

final class IsXmlnsAttributeTest extends TestCase
{
    /**
     *
     * @dataProvider provideTestCases
     */
    public function test_it_knows_xmlns_attributes(DOMNode|\DOMNameSpaceNode $node, bool $expected): void
    {
        static::assertSame($expected, is_xmlns_attribute($node));
    }

    public function provideTestCases()
    {
        $doc = Document::fromXmlString(
            <<<EOXML
            <doc xmlns:test="http://test" xmlns="http://xmlns">
                <item attr="val">Hello</item>
            </doc>
            EOXML
        )->toUnsafeDocument();

        yield [$doc, false];
        yield [$doc->documentElement, false];
        yield [$doc->documentElement->getAttributeNode('xmlns'), true];
        yield [$doc->documentElement->getAttributeNode('xmlns:test'), true];
        yield [$doc->documentElement->firstElementChild, false];
        yield [$doc->documentElement->firstElementChild->attributes->getNamedItem('attr'), false];
        yield [$doc->documentElement->firstElementChild->firstChild, false];
    }
}
