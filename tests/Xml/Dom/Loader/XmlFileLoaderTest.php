<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Loader;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use VeeWee\Tests\Xml\Helper\FillFileTrait;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Loader\xml_file_loader;

final class XmlFileLoaderTest extends TestCase
{
    use FillFileTrait;

    public function test_it_can_load_xml_file(): void
    {
        $doc = new DOMDocument();
        $xml = '<hello />';
        [$file, $handle] = $this->fillFile($xml);
        $loader = xml_file_loader($file);

        $loader($doc);
        fclose($handle);

        static::assertXmlStringEqualsXmlString($xml, $doc->saveXML());
    }

    public function test_it_can_load_with_options(): void
    {
        $doc = new DOMDocument();
        $xml = '<hello><![CDATA[HELLO]]></hello>';
        [$file, $handle] = $this->fillFile($xml);
        $loader = xml_file_loader($file, LIBXML_NOCDATA);

        $loader($doc);
        fclose($handle);

        static::assertSame('<hello>HELLO</hello>', $doc->saveXML($doc->documentElement));
    }
    
    public function test_it_cannot_load_invalid_xml_file(): void
    {
        $doc = new DOMDocument();
        $xml = '<hello';
        [$file, $handle] = $this->fillFile($xml);
        $loader = xml_file_loader($file);

        $this->expectException(RuntimeException::class);
        $this->expectErrorMessage('Could not load the DOM Document');

        $loader($doc);
        fclose($handle);
    }

    public function test_it_throws_exception_on_invalid_file(): void
    {
        $doc = new DOMDocument();
        $loader = xml_file_loader('invalid-file');

        $this->expectException(RuntimeException::class);
        $this->expectErrorMessage('The file "invalid-file" does not exist');

        $loader($doc);
    }
}
