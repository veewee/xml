<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\DOM;

use function VeeWee\Xml\DOM\documentFromXmlString;
use PHPUnit\Framework\TestCase;

/**
 * @covers ::VeeWee\Xml\DOM\documentFromXmlString()
 *
 * @uses ::VeeWee\Xml\DOM\document()
 * @uses ::HappyHelpers\assertions\assertExtensionLoaded()
 * @uses ::HappyHelpers\callables\after
 * @uses ::VeeWee\Xml\DOM\Configurator\withLoader
 * @uses ::VeeWee\Xml\DOM\loader\xmlStringLoader
 * @uses ::HappyHelpers\xml\detectXmlErrors
 * @uses ::HappyHelpers\xml\useInternalErrors
 */
class documentFromXmlStringTest extends TestCase
{
    /** @test */
    public function it_can_create_a_document_from_xml_string(): void
    {
        $doc = documentFromXmlString(
            $xml = '<hello />',
            fn (\DOMDocument $document): \DOMDocument => $document
        );

        self::assertInstanceOf(\DOMDocument::class, $doc);
        self::assertXmlStringEqualsXmlString($xml, $doc->saveXML());
    }
}
