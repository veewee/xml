<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Dom\Loader;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Exception\RuntimeException;
use VeeWee\Xml\Tests\Helper\FillFileTrait;
use function VeeWee\Xml\Dom\Loader\xml_file_loader;

class XmlFileLoaderTest extends TestCase
{
    use FillFileTrait;

    /** @test */
    public function it_can_load_xml_file(): void
    {
        $doc = new \DOMDocument();
        $xml = '<hello />';
        [$file, $handle] = $this->fillFile($xml);
        $loader = xml_file_loader($file);

        $loader($doc);
        fclose($handle);

        self::assertXmlStringEqualsXmlString($xml, $doc->saveXML());
    }

    /** @test */
    public function it_cannot_load_invalid_xml_file(): void
    {
        $doc = new \DOMDocument();
        $xml = '<hello';
        [$file, $handle] = $this->fillFile($xml);
        $loader = xml_file_loader($file);

        $this->expectException(RuntimeException::class);
        $this->expectErrorMessage('Could not load the DOM Document');

        $loader($doc);
        fclose($handle);
    }

    /** @test */
    public function it_throws_exception_on_invalid_file(): void
    {
        $doc = new \DOMDocument();
        $loader = xml_file_loader('invalid-file');

        $this->expectException(RuntimeException::class);
        $this->expectErrorMessage('The file "invalid-file" does not exist');

        $loader($doc);
    }
}
