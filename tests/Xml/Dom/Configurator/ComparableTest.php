<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Configurator;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Configurator\comparable;
use function VeeWee\Xml\Dom\Locator\document_element;
use function VeeWee\Xml\Dom\Mapper\xml_string;

final class ComparableTest extends TestCase
{
    /**
     * @dataProvider provideXmls
     */
    public function test_it_can_canonicalize(string $input, string $expected): void
    {
        $comparable = Document::fromXmlString($input, comparable());
        $actual = xml_string()($comparable->map(document_element()));

        static::assertSame($expected, $actual);
    }

    public function provideXmls()
    {
        yield 'no-action' => [
            '<hello/>',
            '<hello/>',
        ];

        yield 'cdata' => [
            <<<EOXML
            <foo>
               <![CDATA[some stuff]]>
            </foo>
            EOXML,
            <<<EOXML
            <foo>
               some stuff
            </foo>
            EOXML,
        ];

        yield 'comments' => [
            <<<EOXML
            <foo>
               <!-- dont mind me -->
               <bar/>
            </foo>
            EOXML,
            <<<EOXML
            <foo>
              <bar/>
            </foo>
            EOXML,
        ];

        yield 'normalized' => [
            <<<EOXML
            <foo>
            
               <empty></empty>
               
            </foo>
            EOXML,
            <<<EOXML
            <foo>
              <empty/>
            </foo>
            EOXML,
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
                <ns1:baz/>
              </bar>
            </foo>
            EOXML,
        ];
        yield 'sorted-attributes' => [
            <<<EOXML
            <foo xmlns:a="http://a" xmlns:z="http://z" version="1.9" target="universe">
                <item id="1" sku="jos">Jos</item>
                <item sku="jaak" id="2">Jaak</item>
                <item a:sku="jaak" z:id="3">Jul</item>
            </foo>
            EOXML,
            <<<EOXML
            <foo xmlns:ns1="http://z" xmlns:ns2="http://a" target="universe" version="1.9">
              <item id="1" sku="jos">Jos</item>
              <item id="2" sku="jaak">Jaak</item>
              <item ns1:id="3" ns2:sku="jaak">Jul</item>
            </foo>
            EOXML,
        ];
    }
}
