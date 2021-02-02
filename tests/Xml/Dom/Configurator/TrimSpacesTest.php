<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Dom\Configurator;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use function VeeWee\Xml\Dom\Configurator\trim_spaces;
use function VeeWee\Xml\Dom\Mapper\xml_string;

final class TrimSpacesTest extends TestCase
{
    public function testIt_can_trim_contents(): void
    {
        $doc = new DOMDocument();
        $configurator = trim_spaces();
        $result = $configurator($doc);
        $doc->loadXML($xml = '<hello>    <world />     </hello>');

        static::assertSame($doc, $result);
        static::assertFalse($doc->preserveWhiteSpace);
        static::assertFalse($doc->formatOutput);
        static::assertSame('<hello><world/></hello>', xml_string()($doc->documentElement));
    }
}
