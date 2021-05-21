<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Configurator;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Configurator\optimize_namespaces;
use function VeeWee\Xml\Dom\Locator\document_element;
use function VeeWee\Xml\Dom\Mapper\xml_string;

final class OptimizeNamespacesTest extends TestCase
{
    /**
     * @dataProvider provideXmls
     */
    public function test_it_can_optimize_namespaces(string $input, string $expected): void
    {
        $optimized = Document::fromXmlString($input, optimize_namespaces('ns'));
        $actual = xml_string()($optimized->map(document_element()));

        static::assertSame($expected, $actual);
    }

    public function provideXmls()
    {
        yield 'no-action' => [
            '<hello/>',
            '<hello/>',
        ];
        yield 'namespaced' => [
            <<<EOXML
            <foo xmlns:whatever="http://whatever">
                <bar xmlns:whatever="http://whatever">
                    <whatever:baz/>
                </bar>
            </foo>
            EOXML,
            <<<EOXML
            <foo xmlns:ns1="http://whatever">
                <bar>
                    <ns1:baz xmlns:ns1="http://whatever"/>
                </bar>
            </foo>
            EOXML,
        ];
    }
}
