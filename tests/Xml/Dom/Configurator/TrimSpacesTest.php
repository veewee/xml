<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Dom\Configurator;

use function VeeWee\Xml\Dom\Configurator\trim_spaces;
use PHPUnit\Framework\TestCase;
use function VeeWee\Xml\Dom\Mapper\xml_string;

class TrimSpacesTest extends TestCase
{
    /** @test */
    public function it_can_trim_contents(): void
    {
        $doc = new \DOMDocument();
        $configurator = trim_spaces();
        $result = $configurator($doc);
        $doc->loadXML($xml = '<hello>    <world />     </hello>');

        self::assertSame($doc, $result);
        self::assertFalse($doc->preserveWhiteSpace);
        self::assertFalse($doc->formatOutput);
        self::assertSame('<hello><world/></hello>', xml_string()($doc->documentElement));
    }
}
