<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Reader\Matcher;

use Generator;
use VeeWee\Xml\Reader\Node\ElementNode;
use VeeWee\Xml\Reader\Node\NodeSequence;
use function VeeWee\Xml\Reader\Matcher\all;
use function VeeWee\Xml\Reader\Matcher\attribute_value;
use function VeeWee\Xml\Reader\Matcher\document_element;
use function VeeWee\Xml\Reader\Matcher\element_name;
use function VeeWee\Xml\Reader\Matcher\sequence;

final class SequenceTest extends AbstractMatcherTest
{
    public static function provideRealXmlCases(): Generator
    {
        yield 'combined' => [
            sequence(
                document_element(),
                all(
                    element_name('user'),
                    attribute_value('locale', 'nl')
                )
            ),
            <<<'EOXML'
            <root>
                <user locale="nl">Jos</user>
                <user>Bos</user>
                <user>Mos</user>
            </root>
            EOXML,
            [
                '<user locale="nl">Jos</user>',
            ]
        ];
    }

    public static function provideMatcherCases(): Generator
    {
        yield 'it_returns_true_if_no_sequence_ant_matcher' => [
            sequence(),
            new NodeSequence(),
            true
        ];

        yield 'it_returns_false_on_invalid_count' => [
            sequence(static fn () => true),
            new NodeSequence(),
            false
        ];

        yield 'it_returns_false_on_invalid_step' => [
            sequence(static fn () => false),
            new NodeSequence(
                new ElementNode(1, 'item', 'item', '', '', [])
            ),
            false
        ];

        yield 'it_returns_false_on_invalid_step_in_between' => [
            sequence(
                static fn () => true,
                static fn () => false,
                static fn () => true
            ),
            new NodeSequence(
                new ElementNode(1, 'item', 'item', '', '', []),
                new ElementNode(1, 'item', 'item', '', '', []),
                new ElementNode(1, 'item', 'item', '', '', [])
            ),
            false
        ];

        yield 'it_returns_true_if_full_sequence_matches' => [
            sequence(
                static fn () => true,
                static fn () => true,
                static fn () => true
            ),
            new NodeSequence(
                new ElementNode(1, 'item', 'item', '', '', []),
                new ElementNode(1, 'item', 'item', '', '', []),
                new ElementNode(1, 'item', 'item', '', '', [])
            ),
            true
        ];

        yield 'it_returns_false_if_elements_dont_go_deep_enough' => [
            sequence(
                static fn () => true,
                static fn () => true,
                static fn () => true
            ),
            new NodeSequence(
                new ElementNode(1, 'item', 'item', '', '', []),
                new ElementNode(1, 'item', 'item', '', '', []),
            ),
            false
        ];

        yield 'it_returns_false_if_elements_go_deeper' => [
            sequence(
                static fn () => true,
                static fn () => true,
                static fn () => true
            ),
            new NodeSequence(
                new ElementNode(1, 'item', 'item', '', '', []),
                new ElementNode(1, 'item', 'item', '', '', []),
                new ElementNode(1, 'item', 'item', '', '', []),
                new ElementNode(1, 'item', 'item', '', '', []),
            ),
            false
        ];
    }
}
