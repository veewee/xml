<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Configurator;

use \DOM\XMLDocument;
use Exception;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Configurator\loader;

final class LoaderTest extends TestCase
{
    public function test_it_can_load_xml(): void
    {
        $doc = Document::empty()->toUnsafeDocument();
        $xml = '<hello />';

        $loader = loader(static function (\DOM\XMLDocument $doc) use ($xml): void {
            $doc->loadXML($xml);
        });

        $result = $loader($doc);
        static::assertSame($doc, $result);
        static::assertXmlStringEqualsXmlString($xml, $doc->saveXML());
    }


    public function test_it_can_mark_xml_loading_as_failed(): void
    {
        $doc = Document::empty()->toUnsafeDocument();
        $exception = new Exception('Could not load the XML document');
        $loader = loader(static function (\DOM\XMLDocument $doc) use ($exception): void {
            throw $exception;
        });

        $this->expectExceptionObject($exception);
        $loader($doc);
    }
}
