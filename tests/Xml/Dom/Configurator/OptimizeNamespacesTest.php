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
        $canonicalized = Document::fromXmlString($input, optimize_namespaces());
        $actual = xml_string()($canonicalized->map(document_element()));

        static::assertSame($expected, $actual);
    }

    public function provideXmls()
    {
        yield 'no-action' => [
            '<hello/>',
            '<hello/>',
        ];

        yield 'namespaced-root-and-child' => [
            <<<EOXML
            <foo xmlns:ns1="http://whatever">
               <bar xmlns:ns1="http://whatever">
                    <ns1:baz/>
               </bar>
            </foo>
            EOXML,
            <<<EOXML
            <foo xmlns:ns1="http://whatever">
              <bar>
                <ns1:baz/>
              </bar>
            </foo>
            EOXML,
        ];

        yield 'mixed-namespaces' => [
            <<<EOXML
            <foo xmlns:ns1="http://whatever">
               <bar xmlns:otherns="http://whatever">
                    <otherns:baz/>
               </bar>
            </foo>
            EOXML,
            <<<EOXML
            <foo xmlns:ns1="http://whatever">
              <bar>
                <ns1:baz/>
              </bar>
            </foo>
            EOXML,
        ];

        yield 'namespaced-child' => [
            <<<EOXML
            <foo>
               <bar xmlns:ns1="http://whatever">
                    <ns1:baz/>
               </bar>
            </foo>
            EOXML,
            <<<EOXML
            <foo>
              <bar xmlns:ns1="http://whatever">
                <ns1:baz/>
              </bar>
            </foo>
            EOXML,
        ];
    }
}
