<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Configurator;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Configurator\comparable;
use function VeeWee\Xml\Dom\Locator\document_element;
use function VeeWee\Xml\Dom\Mapper\xml_string;

final class ComparableTest extends TestCase
{
    #[DataProvider('provideXmls')]
    public function test_it_can_canonicalize(string $input, string $expected): void
    {
        $comparable = Document::fromXmlString($input, comparable());
        $actual = xml_string()($comparable->map(document_element()));

        static::assertSame($expected, $actual);
    }

    public static function provideXmls()
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
        // Regression test: default xmlns + regular attributes caused C14N to produce
        // duplicate xmlns declarations on libxml 2.9.14, making comparable() hang.
        // @see https://github.com/php/php-src/issues/21548
        yield 'default-xmlns-with-attributes' => [
            <<<EOXML
            <definitions xmlns="http://schemas.xmlsoap.org/wsdl/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" name="Test">
                <types><xsd:schema/></types>
            </definitions>
            EOXML,
            <<<EOXML
            <ns1:definitions name="Test" xmlns:ns1="http://schemas.xmlsoap.org/wsdl/" xmlns:ns2="http://www.w3.org/2001/XMLSchema">
              <ns1:types>
                <ns2:schema/>
              </ns1:types>
            </ns1:definitions>
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
            <foo target="universe" version="1.9" xmlns:ns1="http://a" xmlns:ns2="http://z">
              <item id="1" sku="jos">Jos</item>
              <item id="2" sku="jaak">Jaak</item>
              <item ns1:sku="jaak" ns2:id="3">Jul</item>
            </foo>
            EOXML,
        ];
    }
}
