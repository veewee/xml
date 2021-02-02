<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Dom\Configurator;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use function VeeWee\Xml\Dom\Configurator\pretty_print;
use function VeeWee\Xml\Dom\Mapper\xml_string;

final class PrettyPrintTest extends TestCase
{
    public function testIt_can_trim_contents(): void
    {
        $configurator = pretty_print();

        $doc = new DOMDocument();
        $result = $configurator($doc);
        $doc->loadXML($xml = '<hello>    <world />     </hello>');

        $expected = <<<EOXML
        <hello>
          <world/>
        </hello>
        EOXML;

        static::assertSame($doc, $result);
        static::assertFalse($doc->preserveWhiteSpace);
        static::assertTrue($doc->formatOutput);
        static::assertSame($expected, xml_string()($doc->documentElement));
    }
}
