<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Manipulator\Document;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Locator\document_element;
use function VeeWee\Xml\Dom\Manipulator\Document\optimize_namespaces;
use function VeeWee\Xml\Dom\Mapper\xml_string;

final class OptimizeNamespacesTest extends TestCase
{
    /**
     * @dataProvider provideXmls
     */
    public function test_it_can_optimize_namespaces(string $input, string $expected): void
    {
        $optimized = Document::fromXmlString($input);

        optimize_namespaces($optimized->toUnsafeDocument(), 'ns');

        $actual = xml_string()($optimized->map(document_element()));
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

        yield 'mixed-namespaces' => [
            <<<EOXML
            <foo xmlns:whatever="http://whatever">
                <bar xmlns:otherns="http://otherns">
                    <otherns:baz/>
                </bar>
            </foo>
            EOXML,
            <<<EOXML
            <foo xmlns:ns1="http://otherns" xmlns:ns2="http://whatever">
                <bar>
                    <ns1:baz/>
                </bar>
            </foo>
            EOXML,
        ];

        yield 'namespaced-child' => [
            <<<EOXML
            <foo>
                <bar xmlns:whatever="http://whatever">
                    <whatever:baz/>
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

        yield 'namespaced-attributes' => [
            <<<EOXML
            <foo xmlns:whetever="http://whatever">
                <bar whetever:abc="jello">
                </bar>
            </foo>
            EOXML,
            <<<EOXML
            <foo xmlns:ns1="http://whatever">
                <bar ns1:abc="jello">
                </bar>
            </foo>
            EOXML,
        ];
        yield 'attributes' => [
            <<<EOXML
            <foo xmlns:a="http://a" xmlns:z="http://z" version="1.9" target="universe">
                <item id="1" sku="jos">Jos</item>
                <item sku="jaak" id="2">Jaak</item>
                <item a:sku="jaak" z:id="3">Jul</item>
            </foo>
            EOXML,
            <<<EOXML
            <foo xmlns:ns1="http://a" xmlns:ns2="http://z" version="1.9" target="universe">
                <item id="1" sku="jos">Jos</item>
                <item sku="jaak" id="2">Jaak</item>
                <item ns1:sku="jaak" ns2:id="3">Jul</item>
            </foo>
            EOXML,
        ];
        yield 'default-namespace' => [
            <<<EOXML
            <foo xmlns="http://whatever">
                <bar xmlns:whatever="http://whatever">
                    <whatever:baz/>
                </bar>
            </foo>
            EOXML,
            <<<EOXML
            <ns1:foo xmlns:ns1="http://whatever">
                <ns1:bar>
                    <ns1:baz/>
                </ns1:bar>
            </ns1:foo>
            EOXML,
        ];
    }
}
