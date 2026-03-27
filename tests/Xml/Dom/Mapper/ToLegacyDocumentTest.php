<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Mapper;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Mapper\to_unsafe_legacy_document;

final class ToLegacyDocumentTest extends TestCase
{
    public function test_it_can_convert_to_legacy_document(): void
    {
        $doc = Document::fromXmlString('<root><item>hello</item></root>');
        $legacy = $doc->toUnsafeLegacyDocument();

        static::assertInstanceOf(DOMDocument::class, $legacy);
        static::assertSame('hello', $legacy->getElementsByTagName('item')->item(0)->textContent);
    }

    public function test_it_preserves_document_uri(): void
    {
        $doc = Document::fromXmlString(
            '<root/>',
            \VeeWee\Xml\Dom\Configurator\document_uri('/some/path/file.xml')
        );
        $legacy = $doc->toUnsafeLegacyDocument();

        static::assertSame('/some/path/file.xml', $legacy->documentURI);
    }

    public function test_it_preserves_namespaces(): void
    {
        $xml = '<root xmlns:ns="http://example.com"><ns:item>value</ns:item></root>';
        $doc = Document::fromXmlString($xml);
        $legacy = $doc->toUnsafeLegacyDocument();

        $items = $legacy->getElementsByTagNameNS('http://example.com', 'item');
        static::assertSame(1, $items->length);
        static::assertSame('value', $items->item(0)->textContent);
    }

    public function test_it_can_be_used_as_mapper(): void
    {
        $doc = Document::fromXmlString('<root/>');
        $legacy = $doc->map(to_unsafe_legacy_document());

        static::assertInstanceOf(DOMDocument::class, $legacy);
        static::assertSame('root', $legacy->documentElement->localName);
    }

    public function test_it_preserves_xml_declaration(): void
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<root/>';
        $doc = Document::fromXmlString($xml);
        $legacy = $doc->toUnsafeLegacyDocument();

        static::assertSame('1.0', $legacy->xmlVersion);
        static::assertSame('UTF-8', $legacy->encoding);
    }
}
