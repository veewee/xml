<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Manipulator\Document;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Locator\document_element;
use function VeeWee\Xml\Dom\Manipulator\Document\promote_namespaces;
use function VeeWee\Xml\Dom\Mapper\xml_string;

final class PromoteNamespacesTest extends TestCase
{
    #[DataProvider('provideXmls')]
    public function test_it_can_promote_namespaces(string $input, string $expected): void
    {
        $doc = Document::fromXmlString($input);

        promote_namespaces($doc->toUnsafeDocument());

        $actual = xml_string()($doc->map(document_element()));
        static::assertSame($expected, $actual);
    }

    public static function provideXmls(): iterable
    {
        yield 'no-namespaces' => [
            '<hello/>',
            '<hello/>',
        ];

        yield 'already-on-root' => [
            <<<EOXML
            <foo xmlns:a="http://a">
                <bar><a:baz/></bar>
            </foo>
            EOXML,
            <<<EOXML
            <foo xmlns:a="http://a">
                <bar><a:baz/></bar>
            </foo>
            EOXML,
        ];

        yield 'child-to-root' => [
            <<<EOXML
            <foo>
                <bar xmlns:a="http://a"><a:baz/></bar>
            </foo>
            EOXML,
            <<<EOXML
            <foo xmlns:a="http://a">
                <bar><a:baz/></bar>
            </foo>
            EOXML,
        ];

        yield 'duplicate-on-children' => [
            <<<EOXML
            <foo>
                <bar xmlns:a="http://a"><a:x/></bar>
                <baz xmlns:a="http://a"><a:y/></baz>
            </foo>
            EOXML,
            <<<EOXML
            <foo xmlns:a="http://a">
                <bar><a:x/></bar>
                <baz><a:y/></baz>
            </foo>
            EOXML,
        ];

        yield 'mixed-namespaces' => [
            <<<EOXML
            <foo>
                <bar xmlns:a="http://a"><a:x/></bar>
                <baz xmlns:b="http://b"><b:y/></baz>
            </foo>
            EOXML,
            <<<EOXML
            <foo xmlns:a="http://a" xmlns:b="http://b">
                <bar><a:x/></bar>
                <baz><b:y/></baz>
            </foo>
            EOXML,
        ];

        yield 'conflict-root-vs-child' => [
            <<<EOXML
            <foo xmlns:a="http://one">
                <bar xmlns:a="http://two"><a:baz/></bar>
            </foo>
            EOXML,
            <<<EOXML
            <foo xmlns:a="http://one">
                <bar xmlns:a="http://two"><a:baz/></bar>
            </foo>
            EOXML,
        ];

        yield 'conflict-among-children' => [
            <<<EOXML
            <foo>
                <bar xmlns:a="http://one"><a:x/></bar>
                <baz xmlns:a="http://two"><a:y/></baz>
            </foo>
            EOXML,
            <<<EOXML
            <foo xmlns:a="http://one">
                <bar><a:x/></bar>
                <baz xmlns:a="http://two"><a:y/></baz>
            </foo>
            EOXML,
        ];

        yield 'nested-deep' => [
            <<<EOXML
            <foo><bar><baz xmlns:a="http://a"><a:qux/></baz></bar></foo>
            EOXML,
            <<<EOXML
            <foo xmlns:a="http://a"><bar><baz><a:qux/></baz></bar></foo>
            EOXML,
        ];

        yield 'default-namespace' => [
            <<<EOXML
            <foo>
                <bar xmlns="http://default"><baz/></bar>
            </foo>
            EOXML,
            <<<EOXML
            <foo>
                <bar xmlns="http://default"><baz/></bar>
            </foo>
            EOXML,
        ];

        yield 'default-and-prefixed-on-same-element' => [
            <<<EOXML
            <foo>
                <bar xmlns="http://default" xmlns:a="http://a"><a:baz/></bar>
            </foo>
            EOXML,
            <<<EOXML
            <foo xmlns:a="http://a">
                <bar xmlns="http://default"><a:baz/></bar>
            </foo>
            EOXML,
        ];

        yield 'empty-namespace' => [
            <<<EOXML
            <foo xmlns="">
                <bar/>
            </foo>
            EOXML,
            <<<EOXML
            <foo xmlns="">
                <bar/>
            </foo>
            EOXML,
        ];

        yield 'attributes-with-namespaces' => [
            <<<EOXML
            <foo>
                <bar xmlns:a="http://a" a:attr="val"/>
            </foo>
            EOXML,
            <<<EOXML
            <foo xmlns:a="http://a">
                <bar a:attr="val"/>
            </foo>
            EOXML,
        ];

        yield 'soap-like' => [
            <<<EOXML
            <SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">
                <SOAP-ENV:Body>
                    <tns:getUser xmlns:tns="https://example.com">
                        <tns:id xmlns:tns="https://example.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="xsd:int" xmlns:xsd="http://www.w3.org/2001/XMLSchema">1</tns:id>
                    </tns:getUser>
                </SOAP-ENV:Body>
            </SOAP-ENV:Envelope>
            EOXML,
            <<<EOXML
            <SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tns="https://example.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
                <SOAP-ENV:Body>
                    <tns:getUser>
                        <tns:id xsi:type="xsd:int">1</tns:id>
                    </tns:getUser>
                </SOAP-ENV:Body>
            </SOAP-ENV:Envelope>
            EOXML,
        ];
    }
}
