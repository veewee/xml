<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Manipulator\Xmlns;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Manipulator\Xmlns\rename_element_namespace;

final class RenameTest extends TestCase
{
    /**
     * @dataProvider provideXmls
     * @param callable(): \Dom\Attr $targetLocator
     */
    public function test_it_can_rename_namespaces(string $input, string $expected): void
    {
        $document = Document::fromXmlString($input);
        $target = $document->locateDocumentElement();
        rename_element_namespace($target, 'http://replace', 'foo');

        $actual = $document->stringifyDocumentElement();
        static::assertSame($expected, $actual);
    }

    public function provideXmls()
    {
        yield 'simple' => [
            '<hello xmlns:replace="http://replace"/>',
            '<hello xmlns:foo="http://replace"/>',
        ];
        yield 'namespaced-root-and-child' => [
            <<<EOXML
            <foo xmlns:replace="http://replace">
                <bar xmlns:replace="http://replace">
                    <replace:baz/>
                </bar>
            </foo>
            EOXML,
            <<<EOXML
            <foo xmlns:foo="http://replace">
                <bar>
                    <foo:baz/>
                </bar>
            </foo>
            EOXML,
        ];

        yield 'mixed-namespaces' => [
            <<<EOXML
            <foo xmlns:replace="http://replace">
                <bar xmlns:otherns="http://other">
                    <replace:baz/>
                </bar>
            </foo>
            EOXML,
            <<<EOXML
            <foo xmlns:foo="http://replace">
                <bar xmlns:otherns="http://other">
                    <foo:baz/>
                </bar>
            </foo>
            EOXML,
        ];

        yield 'namespaced-child' => [
            <<<EOXML
            <foo>
                <bar xmlns:replace="http://replace">
                    <replace:baz/>
                </bar>
            </foo>
            EOXML,
            <<<EOXML
            <foo>
                <bar xmlns:foo="http://replace">
                    <foo:baz/>
                </bar>
            </foo>
            EOXML,
        ];

        yield 'namespaced-attributes' => [
            <<<EOXML
            <foo>
                <bar replace:abc="jello" xmlns:replace="http://replace"/>
            </foo>
            EOXML,
            <<<EOXML
            <foo>
                <bar xmlns:foo="http://replace" foo:abc="jello"/>
            </foo>
            EOXML,
        ];

        yield 'namespaced-attributes-in-child' => [
            <<<EOXML
            <foo xmlns:replace="http://replace">
                <bar replace:abc="jello"/>
            </foo>
            EOXML,
            <<<EOXML
            <foo xmlns:foo="http://replace">
                <bar foo:abc="jello"/>
            </foo>
            EOXML,
        ];

        yield 'local-namespace' => [
            <<<EOXML
            <foo xmlns:replace="http://replace">
                <bar xmlns="http://replace">
                    <child/>
                </bar>
            </foo>
            EOXML,
            <<<EOXML
            <foo xmlns:foo="http://replace">
                <foo:bar>
                    <foo:child/>
                </foo:bar>
            </foo>
            EOXML,
        ];

        yield 'default-namespace' => [
            <<<EOXML
            <foo xmlns="http://replace">
                <abc />
                <bar xmlns:whatever="http://replace">
                    <whatever:baz/>
                </bar>
            </foo>
            EOXML,
            <<<EOXML
            <foo:foo xmlns:foo="http://replace">
                <foo:abc/>
                <foo:bar>
                    <foo:baz/>
                </foo:bar>
            </foo:foo>
            EOXML,
        ];
    }
}
