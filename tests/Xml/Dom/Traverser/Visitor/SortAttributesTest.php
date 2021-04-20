<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Traverser\Visitor;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Mapper\xml_string;

final class SortAttributesTest extends TestCase
{
    public function test_it_can_sort_attributes(): void
    {
        $in = <<<EOXML
        <hello xmlns:a="http/a" xmlns:z="http/z" version="1.9" target="universe">
            <item id="1" sku="jos">Jos</item>
            <item sku="jaak" id="2">Jaak</item>
            <item a:sku="jaak" z:id="3">Jul</item>
        </hello>
        EOXML;

        $expected = <<<EOXML
        <hello xmlns:a="http/a" xmlns:z="http/z" target="universe" version="1.9">
            <item id="1" sku="jos">Jos</item>
            <item id="2" sku="jaak">Jaak</item>
            <item a:sku="jaak" z:id="3">Jul</item>
        </hello>
        EOXML;

        $doc = Document::fromXmlString($in);
        $result = $doc->traverse(new SortAttributes());

        static::assertSame($expected, xml_string()($result));
    }
}
