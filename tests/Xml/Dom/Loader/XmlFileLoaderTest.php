<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Loader;

use \DOM\XMLDocument;
use PHPUnit\Framework\TestCase;
use VeeWee\Tests\Xml\Helper\FillFileTrait;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Loader\xml_file_loader;
use function VeeWee\Xml\Dom\Loader\xml_string_loader;

final class XmlFileLoaderTest extends TestCase
{
    use FillFileTrait;

    public function test_it_can_load_xml_file(): void
    {
        $xml = '<hello />';
        [$file, $handle] = $this->fillFile($xml);
        $loader = xml_file_loader($file);

        $doc = $loader();
        fclose($handle);

        static::assertXmlStringEqualsXmlString($xml, $doc->saveXML());
    }

    public function test_it_can_load_with_options(): void
    {
        $xml = '<hello><![CDATA[HELLO]]></hello>';
        [$file, $handle] = $this->fillFile($xml);
        $loader = xml_file_loader($file, LIBXML_NOCDATA);

        $doc = $loader();
        fclose($handle);

        static::assertSame('<hello>HELLO</hello>', $doc->saveXML($doc->documentElement));
    }

    public function test_it_cannot_load_invalid_xml_file(): void
    {
        $xml = '<hello';
        [$file, $handle] = $this->fillFile($xml);
        $loader = xml_file_loader($file);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('XML fragment is not well-formed');

        $doc = $loader();
        fclose($handle);
    }

    public function test_it_throws_exception_on_invalid_file(): void
    {
        $loader = xml_file_loader('invalid-file');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The file "invalid-file" does not exist');

        $loader();
    }

    public function test_it_can_override_charset(): void
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?><hello>héllo</hello>';
        [$file, $handle] = $this->fillFile($xml);
        $loader = xml_file_loader($file, override_encoding: 'Windows-1252');

        $doc = $loader();
        fclose($handle);

        static::assertSame('hÃ©llo', $doc->documentElement->textContent);
        static::assertSame('Windows-1252', $doc->xmlEncoding);
    }
}
