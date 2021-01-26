<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\DOM;

use function HappyHelpers\callables\pipe;
use function VeeWee\Xml\DOM\Configurator\withLoader;
use function VeeWee\Xml\DOM\Configurator\withTrimmedContents;
use function VeeWee\Xml\DOM\Configurator\withUtf8;
use function VeeWee\Xml\DOM\document;
use function VeeWee\Xml\DOM\loader\xmlStringLoader;
use PHPUnit\Framework\TestCase;

/**
 * @covers ::VeeWee\Xml\DOM\document()
 *
 * @uses ::HappyHelpers\assertions\assertExtensionLoaded()
 */
class documentTest extends TestCase
{
    /** @test */
    public function it_can_create_a_document(): void
    {
        $doc = document(fn (\DOMDocument $document): \DOMDocument => $document);

        self::assertInstanceOf(\DOMDocument::class, $doc);
    }

    /**
     * @test
     *
     * @uses ::HappyHelpers\callables\pipe
     * @uses ::VeeWee\Xml\DOM\Configurator\trimContents
     * @uses ::VeeWee\Xml\DOM\Configurator\utf8
     * @uses ::VeeWee\Xml\DOM\Configurator\withLoader
     * @uses ::VeeWee\Xml\DOM\Configurator\withTrimmedContents
     * @uses ::VeeWee\Xml\DOM\Configurator\withUtf8
     * @uses ::VeeWee\Xml\DOM\loader\xmlStringLoader
     * @uses ::HappyHelpers\iterables\foldLeft
     * @uses ::HappyHelpers\xml\detectXmlErrors
     * @uses ::HappyHelpers\xml\useInternalErrors
     */
    public function it_can_add_various_configurators(): void
    {
        $doc = document(pipe(
            withLoader(xmlStringLoader($xml = '<hello />')),
            withTrimmedContents(),
            withUtf8()
        ));

        self::assertInstanceOf(\DOMDocument::class, $doc);
        self::assertFalse($doc->preserveWhiteSpace);
        self::assertFalse($doc->formatOutput);
        self::assertSame('UTF-8', $doc->encoding);
        self::assertXmlStringEqualsXmlString($xml, $doc->saveXML());
    }
}
