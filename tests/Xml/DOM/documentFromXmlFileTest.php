<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\DOM;

use function VeeWee\Xml\DOM\documentFromXmlFile;
use PHPUnit\Framework\TestCase;

/**
 * @covers ::VeeWee\Xml\DOM\documentFromXmlFile()
 *
 * @uses ::VeeWee\Xml\DOM\document()
 * @uses ::HappyHelpers\assertions\assertExtensionLoaded()
 * @uses ::HappyHelpers\callables\after
 * @uses ::VeeWee\Xml\DOM\Configurator\withLoader
 * @uses ::VeeWee\Xml\DOM\loader\xmlFileLoader
 * @uses ::HappyHelpers\xml\detectXmlErrors
 * @uses ::HappyHelpers\xml\useInternalErrors
 */
class documentFromXmlFileTest extends TestCase
{
    /** @test */
    public function it_can_create_a_document_from_xml_string(): void
    {
        [$file, $handle] = $this->fillFile($xml = '<hello />');

        $doc = documentFromXmlFile(
            $file,
            fn (\DOMDocument $document): \DOMDocument => $document
        );

        self::assertInstanceOf(\DOMDocument::class, $doc);
        self::assertXmlStringEqualsXmlString($xml, $doc->saveXML());

        fclose($handle);
    }

    private function fillFile(string $content): array
    {
        $handle = tmpfile();
        $path = stream_get_meta_data($handle)['uri'];
        fwrite($handle, $content);

        return [$path, $handle];
    }
}
