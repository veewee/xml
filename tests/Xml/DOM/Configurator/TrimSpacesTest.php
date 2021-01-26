<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\DOM\Configurator;

use function VeeWee\Xml\Dom\Configurator\trim_spaces;
use PHPUnit\Framework\TestCase;

class TrimSpacesTest extends TestCase
{
    /** @test */
    public function it_can_trim_contents(): void
    {
        $doc = new \DOMDocument();
        $doc->loadXML($xml = '<hello>    <world />     </hello>');

        $configurator = trim_spaces();

        $result = $configurator($doc);
        self::assertSame($doc, $result);
        self::assertFalse($doc->preserveWhiteSpace);
        self::assertFalse($doc->formatOutput);
        self::assertXmlStringEqualsXmlString('<hello><world /></hello>', $doc->saveXML());
    }
}
