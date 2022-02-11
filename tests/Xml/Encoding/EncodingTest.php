<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Encoding;

use JsonSerializable;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Encoding\Exception\EncodingException;
use VeeWee\Xml\Encoding\XmlSerializable;
use function Psl\Fun\identity;
use function VeeWee\Xml\Dom\Locator\document_element;
use function VeeWee\Xml\Encoding\document_encode;
use function VeeWee\Xml\Encoding\element_decode;
use function VeeWee\Xml\Encoding\xml_decode;
use function VeeWee\Xml\Encoding\xml_encode;

final class EncodingTest extends TestCase
{
    /**
     * @dataProvider provideBidirectionalCases
     * @dataProvider provideRiskyBidirectionalCases
     * @dataProvider provideEncodingOnly
     */
    public function test_it_encodes(string $xml, array $data)
    {
        $actual = xml_encode($data, identity());
        static::assertXmlStringEqualsXmlString($xml, $actual);
    }

    /**
     * @dataProvider provideBidirectionalCases
     * @dataProvider provideRiskyBidirectionalCases
     * @dataProvider provideEncodingOnly
     */
    public function test_it_encodes_to_document(string $xml, array $data)
    {
        $actual = document_encode($data, identity());
        static::assertXmlStringEqualsXmlString($xml, $actual->toXmlString());
    }

    /**
     * @dataProvider provideBidirectionalCases
     * @dataProvider provideRiskyBidirectionalCases
     * @dataProvider provideDecodingOnly
     */
    public function test_it_decodes(string $xml, array $data)
    {
        $actual = xml_decode($xml, identity());
        static::assertSame($data, $actual);
    }

    /**
     * @dataProvider provideBidirectionalCases
     * @dataProvider provideDecodingOnly
     */
    public function test_it_decodes_from_element(string $xml, array $data)
    {
        $doc = Document::fromXmlString($xml);
        $element = $doc->locate(document_element());

        $actual = element_decode($element, identity());
        static::assertSame($data, $actual);
    }

    /**
     * @dataProvider provideInvalidXml
     */
    public function test_it_errors_while_encoding_invalid_xml(string $xml, array $data)
    {
        $this->expectException(EncodingException::class);
        xml_encode($data);
    }

    /**
     * @dataProvider provideInvalidXml
     */
    public function test_it_errors_while_decoding_invalid_xml(string $xml)
    {
        $this->expectException(EncodingException::class);
        xml_decode($xml);
    }

    public function provideBidirectionalCases()
    {
        yield 'empty' => [
            'xml' => '<hello />',
            'data' => ['hello' => '']
        ];
        yield 'basic' => [
            'xml' => '<hello>world</hello>',
            'data' => ['hello' => 'world']
        ];
        yield 'value-and-attributes' => [
            'xml' => '<hello default="world">Toon</hello>',
            'data' => ['hello' => [
                '@attributes' => [
                    'default' => 'world',
                ],
                '@value' => 'Toon',
            ]]
        ];
        yield 'attributes-only' => [
            'xml' => '<hello default="world" value="Toon" />',
            'data' => ['hello' => [
                '@attributes' => [
                    'default' => 'world',
                    'value' => 'Toon',
                ],
            ]]
        ];
        yield 'nested-single-child' => [
            'xml' => <<<EOXML
                <root>
                   <item>
                       <id>1</id>
                       <name>X</name>
                       <categories>
                           <category>A</category>
                           <category>B</category>
                           <category>C</category>
                        </categories>
                   </item>     
                </root>
            EOXML,
            'data' => [
                'root' => [
                    'item' => [
                        'id' => '1',
                        'name' => 'X',
                        'categories' => [
                            'category' => ['A', 'B', 'C']
                        ]
                    ]
                ]
            ]
        ];
        yield 'nested-multiple-child' => [
            'xml' => <<<EOXML
                <root>
                   <item>
                       <id>1</id>
                       <name>X</name>
                       <category>A</category>
                       <category>B</category>
                       <category>C</category>
                   </item>
                   <item>
                       <id>2</id>
                       <name>Y</name>
                       <category>M</category>
                       <category>N</category>
                       <category>O</category>
                   </item>   
                </root>
            EOXML,
            'data' => [
                'root' => [
                    'item' => [
                        [
                            'id' => '1',
                            'name' => 'X',
                            'category' => ['A', 'B', 'C']
                        ],
                        [
                            'id' => '2',
                            'name' => 'Y',
                            'category' => ['M', 'N', 'O']
                        ]
                    ]
                ]
            ]
        ];
    }

    public function provideRiskyBidirectionalCases()
    {
        yield 'namespaced' => [
            'xml' => <<<EOXML
                <root xmlns="http://rooty.root" xmlns:test="http://testy.test">
                    <test:item>
                        <id:int xmlns:id="http://identity.id">1</id:int>
                    </test:item>
                </root>
            EOXML,
            'data' => ['root' => [
                '@namespaces' => [
                    'test' => 'http://testy.test',
                    '' => 'http://rooty.root',
                ],
                'test:item' => [
                    'id:int' => [
                        '@namespaces' => [
                            'id' => 'http://identity.id',
                        ],
                        '@value' => '1',
                    ],
                ],
            ]]
        ];
    }

    public function provideEncodingOnly()
    {
        yield 'normalizable-types' => [
            'xml' => <<<EOXML
            <root>
                <id type="1">132</id>
                <xml-array>
                    <field1>123</field1>
                </xml-array>
                <xml-value>2</xml-value>
                <json-array>
                    <field1>999</field1>
                </json-array>
                <json-value>1</json-value>
                <price>121</price>
            </root>
            EOXML,
            'data' => [
                'root' => [
                    'id' => [
                        '@attributes' => [
                            'type' => 1
                        ],
                        '@value' => 132
                    ],
                    'xml-array' => new class implements XmlSerializable {
                        public function xmlSerialize(): array
                        {
                            return [
                                'field1' => 123
                            ];
                        }
                    },
                    'xml-value' => new class implements XmlSerializable {
                        public function xmlSerialize(): int
                        {
                            return 2;
                        }
                    },
                    'json-array' => new class implements JsonSerializable {
                        public function jsonSerialize(): array
                        {
                            return [
                                'field1' => 999
                            ];
                        }
                    },
                    'json-value' => new class implements JsonSerializable {
                        public function jsonSerialize(): mixed
                        {
                            return 1;
                        }
                    },
                    'price' => 121
                ]
            ]
        ];
    }

    public function provideDecodingOnly()
    {
        yield 'cdata' => [
            'xml' => '<hello><![CDATA[Jos & Bos]]></hello>',
            'data' => ['hello' => 'Jos & Bos']
        ];
    }

    public function provideInvalidXml()
    {
        yield 'items-in-root' => [
            'xml' => <<<EOXML
                <item>Hello</item>
                <item>World</item>
            EOXML,
            'data' => [
                'hello',
                'world',
            ]
        ];
    }
}
