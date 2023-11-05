<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Traverser\Visitor;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Dom\Traverser\Visitor\RemoveNamespaces;
use function VeeWee\Xml\Dom\Mapper\xml_string;

final class RemoveNamespacesTest extends TestCase
{
    public function test_it_can_remove_all_namespaces(): void
    {
        $in = <<<EOXML
        <hello xmlns:a="http/a" xmlns:z="http/z" version="1.9" target="universe">
            <item xmlns="http://raw" id="1" sku="jos">Jos</item>
            <x:item xmlns:x="http://x" sku="jaak" id="2">Jaak</x:item>
            <item a:sku="jaak" z:id="3">Jul</item>
        </hello>
        EOXML;

        $expected = <<<EOXML
        <hello version="1.9" target="universe">
            <item id="1" sku="jos">Jos</item>
            <item sku="jaak" id="2">Jaak</item>
            <item sku="jaak" id="3">Jul</item>
        </hello>
        EOXML;

        $doc = Document::fromXmlString($in);
        $result = $doc->traverse(RemoveNamespaces::all());

        static::assertSame($expected, xml_string()($result));
    }

    public function test_it_can_remove_unprefixed_namespaces(): void
    {
        $in = <<<EOXML
        <hello xmlns:a="http/a" xmlns:z="http/z" version="1.9" target="universe">
            <item xmlns="http://raw" id="1" sku="jos">Jos</item>
            <x:item xmlns:x="http://x" sku="jaak" id="2">Jaak</x:item>
            <item a:sku="jaak" z:id="3">Jul</item>
        </hello>
        EOXML;

        $expected = <<<EOXML
        <hello xmlns:a="http/a" xmlns:z="http/z" version="1.9" target="universe">
            <item id="1" sku="jos">Jos</item>
            <x:item xmlns:x="http://x" sku="jaak" id="2">Jaak</x:item>
            <item a:sku="jaak" z:id="3">Jul</item>
        </hello>
        EOXML;

        $doc = Document::fromXmlString($in);
        $result = $doc->traverse(RemoveNamespaces::unprefixed());

        static::assertSame($expected, xml_string()($result));
    }

    public function test_it_can_remove_prefixed_namespaces(): void
    {
        $in = <<<EOXML
        <hello xmlns:a="http/a" xmlns:z="http/z" version="1.9" target="universe">
            <item xmlns="http://raw" id="1" sku="jos">Jos</item>
            <x:item xmlns:x="http://x" sku="jaak" id="2">Jaak</x:item>
            <item a:sku="jaak" z:id="3">Jul</item>
        </hello>
        EOXML;

        $expected = <<<EOXML
        <hello version="1.9" target="universe">
            <item xmlns="http://raw" id="1" sku="jos">Jos</item>
            <item sku="jaak" id="2">Jaak</item>
            <item sku="jaak" id="3">Jul</item>
        </hello>
        EOXML;

        $doc = Document::fromXmlString($in);
        $result = $doc->traverse(RemoveNamespaces::prefixed());

        static::assertSame($expected, xml_string()($result));
    }

    public function test_it_can_remove_namespaces_by_prefix_name(): void
    {
        $in = <<<EOXML
        <hello xmlns:a="http/a" xmlns:z="http/z" version="1.9" target="universe">
            <item xmlns="http://raw" id="1" sku="jos">Jos</item>
            <x:item xmlns:x="http://x" sku="jaak" id="2">Jaak</x:item>
            <item a:sku="jaak" z:id="3">Jul</item>
        </hello>
        EOXML;

        $expected = <<<EOXML
        <hello version="1.9" target="universe">
            <item id="1" sku="jos">Jos</item>
            <x:item xmlns:x="http://x" sku="jaak" id="2">Jaak</x:item>
            <item sku="jaak" id="3">Jul</item>
        </hello>
        EOXML;

        $doc = Document::fromXmlString($in);
        $result = $doc->traverse(RemoveNamespaces::byPrefixNames(['', 'a', 'z']));

        static::assertSame($expected, xml_string()($result));
    }

    public function test_it_can_remove_namespaces_by_prefix__uri(): void
    {
        $in = <<<EOXML
        <hello xmlns:a="http/a" xmlns:z="http/z" version="1.9" target="universe">
            <item xmlns="http://raw" id="1" sku="jos">Jos</item>
            <x:item xmlns:x="http://x" sku="jaak" id="2">Jaak</x:item>
            <item a:sku="jaak" z:id="3">Jul</item>
        </hello>
        EOXML;

        $expected = <<<EOXML
        <hello xmlns:z="http/z" version="1.9" target="universe">
            <item id="1" sku="jos">Jos</item>
            <x:item xmlns:x="http://x" sku="jaak" id="2">Jaak</x:item>
            <item sku="jaak" z:id="3">Jul</item>
        </hello>
        EOXML;

        $doc = Document::fromXmlString($in);
        $result = $doc->traverse(RemoveNamespaces::byNamespaceURIs(['http/a', 'http://raw']));

        static::assertSame($expected, xml_string()($result));
    }
}
