<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Configurator;

use \DOM\XMLDocument;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Configurator\utf8;

final class Utf8Test extends TestCase
{
    public function test_it_can_convert_to_utf8(): void
    {
        $doc = Document::fromXmlString($xml = '<hello />')->toUnsafeDocument();

        $configurator = utf8();

        $result = $configurator($doc);
        static::assertSame($doc, $result);
        static::assertSame('UTF-8', $doc->encoding);
    }
}
