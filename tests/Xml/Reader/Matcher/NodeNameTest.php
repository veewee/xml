<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Reader\Matcher;

use Generator;
use VeeWee\Xml\Reader\Node\ElementNode;
use VeeWee\Xml\Reader\Node\NodeSequence;
use function VeeWee\Xml\Reader\Matcher\node_name;

/**
 * @deprecated Use element_name instead! This will be removed in next major version
 */
final class NodeNameTest extends AbstractMatcherTest
{
    public static function provideRealXmlCases(): Generator
    {
        yield 'users' => [
            node_name('user'),
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
            node_name('u:user'),
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
            new ElementNode(1, 'item', 'item', '', '', [])
        );

        yield 'it_returns_true_if_element_name_matches' => [
            node_name('item'),
            $sequence,
            true
        ];

        yield 'it_returns_false_if_element_name_does_not_match' => [
            node_name('other'),
            $sequence,
            false
        ];
    }
}
