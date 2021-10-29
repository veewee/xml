<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Manipulator\Xmlns;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Manipulator\Xmlns\rename;
use function VeeWee\Xml\Dom\Mapper\xml_string;

final class RenameTest extends TestCase
{
    /**
     * @dataProvider provideXmls
     */
    public function test_it_can_rename_namespaces(string $input, string $expected): void
    {
        $document = Document::fromXmlString($input)->toUnsafeDocument();
        rename($document, 'http://replace', 'foo');

        $actual = xml_string()($document->documentElement);
        static::assertSame($expected, $actual);
    }

    
    public function test_it_can_not_rename_existing_prefix_to_other_uri(): void
    {
        $document = Document::fromXmlString('<hello xmlns:ns1="http://ok" />')->toUnsafeDocument();

        $this->expectException(RuntimeException::class);
        $this->expectErrorMessage('Cannot rename the namespace uri http://replace because the prefix ns1 is already linked to uri http://ok');
        rename($document, 'http://replace', 'ns1');
    }

    public function provideXmls()
    {
        yield 'no-action' => [
            '<hello/>',
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
                    <foo:baz xmlns:foo="http://replace"/>
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
                    <foo:baz xmlns:foo="http://replace"/>
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
            <foo xmlns:foo="http://replace">
                <bar>
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
            <foo xmlns:foo="http://replace">
                <bar foo:abc="jello"/>
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
                <foo:bar xmlns:foo="http://replace">
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
