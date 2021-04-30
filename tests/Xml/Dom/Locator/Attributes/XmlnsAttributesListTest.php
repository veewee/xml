<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Locator\Attributes;

use DOMElement;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Locator\Attributes\xmlns_attributes_list;

final class XmlnsAttributesListTest extends TestCase
{
    /**
     * @dataProvider provideTestCases
     */
    public function test_it_can_fetch_xmlns_attribute_list_from_element(DOMElement $node, array $expected): void
    {
        $actual = xmlns_attributes_list($node);

        self::assertEquals($expected, [...$actual]);
    }

    
    public function test_it_can_fetch_attribute_list_from_empty_element(): void
    {
        static::assertSame();
    }

    
    public function test_it_can_fetch_attribute_list_from_non_element_node(): void
    {
        static::assertSame();
    }

    public function provideTestCases()
    {
        $doc = Document::fromXmlString(
            <<<EOXML
            <doc xmlns:test="http://test" xmlns="http://xmlns">
                <item attr="val" xmlns:yo="https://yo.com">Hello</item>
            </doc>
            EOXML
        )->toUnsafeDocument();

        yield [$doc->documentElement, [
            $doc->documentElement->getAttributeNode('xmlns'),
            $doc->documentElement->getAttributeNode('xmlns:test'),
        ]];
        yield [$doc->documentElement->firstElementChild, [
            $doc->documentElement->firstElementChild->getAttributeNode('xmlns:yo'),
        ]];
    }
}
