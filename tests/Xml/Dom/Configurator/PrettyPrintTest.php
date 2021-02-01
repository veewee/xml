<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Dom\Configurator;

use function VeeWee\Xml\Dom\Configurator\pretty_print;
use PHPUnit\Framework\TestCase;
use function VeeWee\Xml\Dom\Mapper\xml_string;

class PrettyPrintTest extends TestCase
{
    /** @test */
    public function it_can_trim_contents(): void
    {
        $configurator = pretty_print();

        $doc = new \DOMDocument();
        $result = $configurator($doc);
        $doc->loadXML($xml = '<hello>    <world />     </hello>');

        $expected = <<<EOXML
        <hello>
          <world/>
        </hello>
        EOXML;

        self::assertSame($doc, $result);
        self::assertFalse($doc->preserveWhiteSpace);
        self::assertTrue($doc->formatOutput);
        self::assertSame($expected, xml_string()($doc->documentElement));
    }
}
