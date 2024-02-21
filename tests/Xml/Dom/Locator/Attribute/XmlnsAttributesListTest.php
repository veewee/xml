<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Locator\Attribute;

use \DOM\Node;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Locator\Attribute\xmlns_attributes_list;

final class XmlnsAttributesListTest extends TestCase
{
    /**
     * @dataProvider provideTestCases
     */
    public function test_it_can_fetch_xmlns_attribute_list_from_node(\DOM\Node $node, array $expected): void
    {
        $actual = xmlns_attributes_list($node);

        static::assertEquals($expected, [...$actual]);
    }

    public function provideTestCases()
    {
        $doc = Document::fromXmlString(
            <<<EOXML
            <doc xmlns:test="http://test" xmlns="http://xmlns">
                <item attr="val" xmlns:yo="https://yo.com">Hello</item>
                <empty />
                <attr noelement="true" />
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
        yield [$doc->documentElement->getElementsByTagName('empty')->item(0), []];
        yield [$doc->documentElement->getElementsByTagName('attr')->item(0)->getAttributeNode('noelement'), []];
    }
}
