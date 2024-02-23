<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Configurator;

use \DOM\XMLDocument;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Configurator\trim_spaces;
use function VeeWee\Xml\Dom\Mapper\xml_string;

final class TrimSpacesTest extends TestCase
{
    public function test_it_can_trim_contents(): void
    {
        $doc = Document::fromXmlString('<hello>    <world />     </hello>')->toUnsafeDocument();
        $configurator = trim_spaces();
        $result = $configurator($doc);

        static::assertSame($doc, $result);
        // TODO : static::assertFalse($doc->preserveWhiteSpace);
        static::assertFalse($doc->formatOutput);
        static::assertSame('<hello><world/></hello>', xml_string()($doc->documentElement));
    }
}
