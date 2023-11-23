<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Loader;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Loader\xml_string_loader;

final class XmlStringLoaderTest extends TestCase
{
    public function test_it_can_load_xml_string(): void
    {
        $doc = new DOMDocument();
        $xml = '<hello />';
        $loader = xml_string_loader($xml);

        $loader($doc);
        static::assertXmlStringEqualsXmlString($xml, $doc->saveXML());
    }

    public function test_it_can_not_load_invalid_xml_string(): void
    {
        $doc = new DOMDocument();
        $xml = '<hello';
        $loader = xml_string_loader($xml);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Could not load the DOM Document');

        $loader($doc);
    }

    public function test_it_can_load_with_options(): void
    {
        $doc = new DOMDocument();
        $xml = '<hello><![CDATA[HELLO]]></hello>';
        $loader = xml_string_loader($xml, LIBXML_NOCDATA);

        $loader($doc);
        static::assertSame('<hello>HELLO</hello>', $doc->saveXML($doc->documentElement));
    }
}
