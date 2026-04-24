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
            // When two sibling elements use the same prefix for different URIs,
            // the first wins the root spot; on legacy DOM, libxml reconciles the
            // second subtree and renames its prefix (e.g. `a` -> `a1`) so both
            // namespaces end up declared on the document element. This is the
            // expected outcome — all declarations are promoted, just with
            // prefix disambiguation. Upgrade to veewee/xml 4.x if you need the
            // original prefix preserved on the conflicting subtree.
            <<<EOXML
            <foo>
                <bar xmlns:a="http://one"><a:x/></bar>
                <baz xmlns:a="http://two"><a:y/></baz>
            </foo>
            EOXML,
            <<<EOXML
            <foo xmlns:a="http://one" xmlns:a1="http://two">
                <bar><a:x/></bar>
                <baz xmlns:a="http://two"><a1:y/></baz>
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
            // When an element carries both a default xmlns and a prefixed
            // xmlns:* declaration, only the prefixed one is promoted. On
            // legacy DOM, libxml's reconciliation additionally rewrites the
            // element's default namespace as a prefixed one on the root (here
            // `<bar>` becomes `<default:bar>`). The result remains
            // semantically identical XML.
            <<<EOXML
            <foo>
                <bar xmlns="http://default" xmlns:a="http://a"><a:baz/></bar>
            </foo>
            EOXML,
            <<<EOXML
            <foo xmlns:a="http://a" xmlns:default="http://default">
                <default:bar xmlns="http://default"><a:baz/></default:bar>
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

        yield 'conflict-among-children-with-xsi-type' => [
            // Pins the interaction between libxml's legacy-DOM prefix
            // disambiguation (see `conflict-among-children`) and an
            // xsi:type attribute whose value references a conflicting
            // prefix. Because xsi:type values are opaque strings at the
            // DOM level, libxml cannot rewrite the `a:Thing` reference to
            // match the renamed element prefix. In this case the
            // declaration on `<baz>` shadows the root one so the
            // xsi:type still resolves to http://two, but callers that
            // emit such documents should upgrade to veewee/xml 4.x, which
            // preserves prefixes exactly.
            <<<EOXML
            <foo>
                <bar xmlns:a="http://one"><a:x/></bar>
                <baz xmlns:a="http://two" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><a:y xsi:type="a:Thing"/></baz>
            </foo>
            EOXML,
            <<<EOXML
            <foo xmlns:a="http://one" xmlns:a1="http://two" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
                <bar><a:x/></bar>
                <baz xmlns:a="http://two"><a1:y xsi:type="a:Thing"/></baz>
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
