<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Configurator;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Configurator\pretty_print;
use function VeeWee\Xml\Dom\Mapper\xml_string;

final class PrettyPrintTest extends TestCase
{
    public function test_it_can_pretty_print_contents(): void
    {
        $configurator = pretty_print();

        $doc = Document::fromXmlString($xml = '<hello>    <world />     </hello>')->toUnsafeDocument();
        $result = $configurator($doc);

        $expected = <<<EOXML
        <hello>
          <world/>
        </hello>
        EOXML;

        static::assertNotSame($doc, $result);
        static::assertTrue($result->formatOutput);
        static::assertSame($expected, xml_string()($result->documentElement));
    }

    public function test_it_can_pretty_print_empty_xml(): void
    {
        $configurator = pretty_print();

        $doc = Document::empty()->toUnsafeDocument();
        $result = $configurator($doc);

        $result->append(
            $hello = $result->createElement('hello')
        );
        $hello->append($result->createElement('world'));

        $expected = <<<EOXML
        <hello>
          <world/>
        </hello>
        EOXML;

        static::assertNotSame($doc, $result);
        static::assertTrue($result->formatOutput);
        static::assertSame($expected, xml_string()($result->documentElement));
    }
}
