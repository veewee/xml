<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Configurator;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Configurator\canonicalize;
use function VeeWee\Xml\Dom\Locator\document_element;
use function VeeWee\Xml\Dom\Mapper\xml_string;

final class CanonicalizeTest extends TestCase
{
    /**
     * @dataProvider provideXmls
     */
    public function test_it_can_canonicalize(string $input, string $expected): void
    {
        $canonicalized = Document::fromXmlString($input, canonicalize());
        $actual = xml_string()($canonicalized->map(document_element()));

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
        yield 'non-optimizable' => [
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
