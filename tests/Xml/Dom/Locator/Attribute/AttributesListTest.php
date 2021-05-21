<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Locator\Attribute;

use DOMNode;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Locator\Attribute\attributes_list;

final class AttributesListTest extends TestCase
{
    /**
     * @dataProvider provideTestCases
     */
    public function test_it_can_fetch_xmlns_attribute_list_from_node(DOMNode $node, array $expected): void
    {
        $actual = attributes_list($node);

        static::assertEquals($expected, [...$actual]);
    }

    public function provideTestCases()
    {
        $doc = Document::fromXmlString(
            <<<EOXML
            <doc attr1="yes" attr2="yes" xmlns="https://yolo" xmlns:yo="https://yo.com">
                <item attr="val" >Hello</item>
                <empty />
            </doc>
            EOXML
        )->toUnsafeDocument();

        yield [$doc->documentElement, [
            $doc->documentElement->getAttributeNode('attr1'),
            $doc->documentElement->getAttributeNode('attr2'),
        ]];
        yield [$doc->documentElement->firstElementChild, [
            $doc->documentElement->firstElementChild->getAttributeNode('attr'),
        ]];
        yield [$doc->documentElement->getElementsByTagName('empty')->item(0), []];
        yield [$doc->documentElement->getAttributeNode('attr1'), []];
    }
}
