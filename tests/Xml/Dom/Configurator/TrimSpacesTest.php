<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Configurator;

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

        static::assertNotSame($doc, $result);
        static::assertFalse($result->formatOutput);
        static::assertSame('<hello><world/></hello>', xml_string()($result->documentElement));
    }

    public function test_it_can_trim_spaces_on_empty_xml(): void
    {
        $configurator = trim_spaces();

        $doc = Document::empty()->toUnsafeDocument();
        $result = $configurator($doc);

        $result->append(
            $hello = $result->createElement('hello')
        );
        $hello->append($result->createElement('world'));

        $expected = <<<EOXML
        <hello><world/></hello>
        EOXML;

        static::assertNotSame($doc, $result);
        static::assertFalse($result->formatOutput);
        static::assertSame($expected, xml_string()($result->documentElement));
    }
}
