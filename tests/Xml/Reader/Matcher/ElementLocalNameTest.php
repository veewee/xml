<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Reader\Matcher;

use Generator;
use VeeWee\Xml\Reader\Node\ElementNode;
use VeeWee\Xml\Reader\Node\NodeSequence;
use function VeeWee\Xml\Reader\Matcher\element_local_name;

final class ElementLocalNameTest extends AbstractMatcherTest
{
    public static function provideRealXmlCases(): Generator
    {
        yield 'users' => [
            element_local_name('user'),
            <<<'EOXML'
            <root>
                <user>Jos</user>
                <user>Bos</user>
                <user>Mos</user>
            </root>
            EOXML,
            [
                '<user>Jos</user>',
                '<user>Bos</user>',
                '<user>Mos</user>'
            ]
        ];
        yield 'namespaced' => [
            element_local_name('user'),
            <<<'EOXML'
            <root xmlns:u="https://users">
                <u:user>Jos</u:user>
                <u:user>Bos</u:user>
                <u:user>Mos</u:user>
            </root>
            EOXML,
            [
                '<u:user xmlns:u="https://users">Jos</u:user>',
                '<u:user xmlns:u="https://users">Bos</u:user>',
                '<u:user xmlns:u="https://users">Mos</u:user>'
            ]
        ];
    }

    public static function provideMatcherCases(): Generator
    {
        $sequence = new NodeSequence(
            new ElementNode(1, 'x:item', 'item', 'https://x', 'x', [])
        );

        yield 'it_returns_true_if_local_element_name_matches' => [
            element_local_name('item'),
            $sequence,
            true
        ];

        yield 'it_returns_false_if_local_element_name_does_not_match' => [
            element_local_name('other'),
            $sequence,
            false
        ];
    }
}
