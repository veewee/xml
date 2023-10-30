<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Reader\Matcher;

use Generator;
use VeeWee\Xml\Reader\Node\ElementNode;
use VeeWee\Xml\Reader\Node\NodeSequence;
use function VeeWee\Xml\Reader\Matcher\element_position;

final class ElementPositionTest extends AbstractMatcherTest
{
    public static function provideRealXmlCases(): Generator
    {
        yield 'users' => [
            element_position(2),
            <<<'EOXML'
            <root>
                <user>Jos</user>
                <user>Bos</user>
                <user>Mos</user>
            </root>
            EOXML,
            [
                '<user>Bos</user>',
            ]
        ];
    }

    public static function provideMatcherCases(): Generator
    {
        yield 'it_returns_true_if_element_position_matches' => [
            element_position(1),
            new NodeSequence(
                new ElementNode(1, 'item', 'item', '', '', [])
            ),
            true
        ];

        yield 'it_returns_false_if_element_name_does_not_match' => [
            element_position(1),
            new NodeSequence(
                new ElementNode(2, 'item', 'item', '', '', [])
            ),
            false
        ];
    }
}
