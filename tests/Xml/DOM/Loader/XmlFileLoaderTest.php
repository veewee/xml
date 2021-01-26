<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\DOM\Loader;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Loader\xml_file_loader;

class XmlFileLoaderTest extends TestCase
{
    /** @test */
    public function it_can_load_xml_file(): void
    {
        $doc = new \DOMDocument();
        $xml = '<hello />';
        [$file, $handle] = $this->fillFile($xml);
        $loader = xml_file_loader($file);

        $result = $loader($doc);
        fclose($handle);

        self::assertTrue($result->getResult());
        self::assertXmlStringEqualsXmlString($xml, $doc->saveXML());
    }

    /** @test */
    public function it_cannot_load_invalid_xml_file(): void
    {
        $doc = new \DOMDocument();
        $xml = '<hello';
        [$file, $handle] = $this->fillFile($xml);
        $loader = xml_file_loader($file);

        $result = $loader($doc);
        fclose($handle);

        $this->expectException(RuntimeException::class);
        $this->expectErrorMessage('Could not load the DOM Document');
        $result->getResult();
    }

    /** @test */
    public function it_throws_exception_on_invalid_file(): void
    {
        $doc = new \DOMDocument();
        $loader = xml_file_loader('invalid-file');

        $result = $loader($doc);

        $this->expectException(RuntimeException::class);
        $this->expectErrorMessage('The file "invalid-file" does not exist');
        $result->getResult();
    }

    private function fillFile(string $content): array
    {
        $handle = tmpfile();
        $path = stream_get_meta_data($handle)['uri'];
        fwrite($handle, $content);

        return [$path, $handle];
    }
}
