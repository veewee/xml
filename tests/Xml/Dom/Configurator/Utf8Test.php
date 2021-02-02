<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Dom\Configurator;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use function VeeWee\Xml\Dom\Configurator\utf8;

final class Utf8Test extends TestCase
{
    public function testIt_can_convert_to_utf8(): void
    {
        $doc = new DOMDocument();
        $doc->loadXML($xml = '<hello />');

        $configurator = utf8();

        $result = $configurator($doc);
        static::assertSame($doc, $result);
        static::assertSame('UTF-8', $doc->encoding);
    }
}
