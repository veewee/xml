<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Loader;

use Dom\XMLDocument;
use PHPUnit\Framework\TestCase;
use function VeeWee\Xml\Dom\Loader\xml_document_loader;

final class XmlDocumentLoaderTest extends TestCase
{
    public function test_it_can_load_xml_string(): void
    {
        $initialDoc = XMLDocument::createFromString('<hello />');
        $loader = xml_document_loader($initialDoc);
        $doc = $loader();

        static::assertXmlStringEqualsXmlString($initialDoc->saveXml(), $doc->saveXML());
    }

    public function test_it_can_load_xml_string_with_different_charset(): void
    {
        $initialDoc = XMLDocument::createFromString('<hello>héllo</hello>');
        $loader = xml_document_loader($initialDoc, override_encoding: 'Windows-1252');
        $doc = $loader();

        static::assertNotSame($initialDoc, $doc);
        static::assertSame('hÃ©llo', $doc->documentElement->textContent);
        static::assertSame('Windows-1252', $doc->xmlEncoding);
    }

    public function test_it_can_load_with_options(): void
    {
        $initialDoc = XMLDocument::createFromString('<hello><![CDATA[HELLO]]></hello>');
        $loader = xml_document_loader($initialDoc, options: LIBXML_NOCDATA);
        $doc = $loader();

        static::assertNotSame($initialDoc, $doc);
        static::assertSame('<hello>HELLO</hello>', $doc->saveXML($doc->documentElement));
    }

    public function test_it_can_load_empty_xml_string(): void
    {
        $initialDoc = XMLDocument::createEmpty(version: '1.1', encoding: 'ASCII');
        $loader = xml_document_loader($initialDoc);
        $doc = $loader();

        static::assertSame($initialDoc->saveXml(), $doc->saveXML());
        static::assertSame('ASCII', $doc->xmlEncoding);
        static::assertSame('1.1', $doc->xmlVersion);
    }

}
