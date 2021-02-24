<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Encoding;

use PHPUnit\Framework\TestCase;
use Psl\Collection\Vector;
use Psl\Type\TypeInterface;
use function Psl\Fun\identity;
use function Psl\Type\int;
use function Psl\Type\shape;
use function Psl\Type\string;
use function Psl\Type\vector;
use function VeeWee\Xml\Encoding\typed;

final class TypedTest extends TestCase
{
    /**
     * @dataProvider provideTypedTestCases
     */
    public function test_typed(string $xml, TypeInterface $type, array $data)
    {
        $actual = typed($xml, $type, identity());

        static::assertEquals($data, $actual);
    }

    public function provideTypedTestCases()
    {
        yield 'nested-single-child' => [
            'xml' => <<<EOXML
                <root>
                   <item>
                       <id>1</id>
                       <name>X</name>
                       <category>A</category>
                       <category>B</category>
                       <category>C</category>
                   </item>     
                </root>
            EOXML,
            'type' => shape([
                'root' => shape([
                    'item' => shape([
                        'id' => int(),
                        'name' => string(),
                        'category' => vector(string()),
                    ])
                ])
            ]),
            'data' => [
                'root' => [
                    'item' => [
                        'id' => 1,
                        'name' => 'X',
                        'category' => new Vector(['A', 'B', 'C'])
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
            'type' => shape([
                'root' => shape([
                    'item' => vector(
                        shape([
                            'id' => int(),
                            'name' => string(),
                            'category' => vector(string()),
                        ])
                    )
                ])
            ]),
            'data' => [
                'root' => [
                    'item' => new Vector([
                        [
                            'id' => 1,
                            'name' => 'X',
                            'category' => new Vector(['A', 'B', 'C'])
                        ],
                        [
                            'id' => 2,
                            'name' => 'Y',
                            'category' => new Vector(['M', 'N', 'O'])
                        ]
                    ])
                ]
            ]
        ];
    }
}
