<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Dom\Configurator;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use function VeeWee\Xml\Dom\Configurator\loader;

class LoaderTest extends TestCase
{
    /** @test */
    public function it_can_load_xml(): void
    {
        $doc = new DOMDocument();
        $xml = '<hello />';

        $loader = loader(static function (DOMDocument $doc) use ($xml): void {
            $doc->loadXML($xml);
        });

        $result = $loader($doc);
        self::assertSame($doc, $result);
        self::assertXmlStringEqualsXmlString($xml, $doc->saveXML());
    }

    /** @test */
    public function it_can_mark_xml_loading_as_failed(): void
    {
        $doc = new DOMDocument();
        $exception = new \Exception('Could not load the XML document');
        $loader = loader(static function (DOMDocument $doc) use ($exception): void {
            throw $exception;
        });

        $this->expectExceptionObject($exception);
        $loader($doc);
    }
}
