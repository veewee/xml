<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\DOM\Configurator;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use function Psl\Result\wrap;
use function VeeWee\Xml\Dom\Configurator\loader;
use function VeeWee\Xml\Dom\Loader\load;

class LoaderTest extends TestCase
{
    /** @test */
    public function it_can_load_xml(): void
    {
        $doc = new DOMDocument();
        $xml = '<hello />';

        $loader = loader(static fn (DOMDocument $doc) => wrap(function () use ($xml, $doc) {
            $doc->loadXML($xml);
            return true;
        }));

        $result = $loader($doc);
        self::assertSame($doc, $result);
        self::assertXmlStringEqualsXmlString($xml, $doc->saveXML());
    }

    /** @test */
    public function it_can_mark_xml_loading_as_failed(): void
    {
        $doc = new DOMDocument();
        $exception = new \Exception('Could not load the XML document');
        $loader = loader(static fn (DOMDocument $doc) => wrap(function () use ($exception) {
            throw $exception;
        }));

        $this->expectExceptionObject($exception);
        $loader($doc);
    }
}
