<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Encoding;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Encoding\Exception\EncodingException;
use VeeWee\Xml\Exception\RuntimeException;
use function Psl\Fun\identity;
use function VeeWee\Xml\Encoding\decode;
use function VeeWee\Xml\Encoding\encode;

final class EncodingTest extends TestCase
{
    /**
     * @dataProvider provideBidirectionalCases
     */
    public function test_it_encodes(string $xml, array $data)
    {
        $actual = encode($data, identity());
        static::assertXmlStringEqualsXmlString($xml, $actual);
    }

    /**
     * @dataProvider provideBidirectionalCases
     */
    public function test_it_decodes(string $xml, array $data)
    {
        $actual = decode($xml, identity());
        static::assertSame($data, $actual);
    }

    /**
     * @dataProvider provideInvalidXml
     */
    public function test_it_errors_while_encoding_invalid_xml(string $xml, array $data)
    {
        $this->expectException(EncodingException::class);
        encode($data);
    }

    /**
     * @dataProvider provideInvalidXml
     */
    public function test_it_errors_while_decoding_invalid_xml(string $xml)
    {
        $this->expectException(RuntimeException::class);
        decode($xml);
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
                    '' => 'http://rooty.root',
                    'test' => 'http://testy.test',
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
