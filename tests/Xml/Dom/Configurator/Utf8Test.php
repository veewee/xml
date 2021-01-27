<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Dom\Configurator;

use function VeeWee\Xml\Dom\Configurator\utf8;
use PHPUnit\Framework\TestCase;

class Utf8Test extends TestCase
{
    /** @test */
    public function it_can_convert_to_utf8(): void
    {
        $doc = new \DOMDocument();
        $doc->loadXML($xml = '<hello />');

        $configurator = utf8();

        $result = $configurator($doc);
        self::assertSame($doc, $result);
        self::assertSame('UTF-8', $doc->encoding);
    }
}
