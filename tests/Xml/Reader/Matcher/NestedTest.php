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
use function VeeWee\Xml\Reader\Matcher\nested;
use function VeeWee\Xml\Reader\Matcher\sequence;

final class NestedTest extends AbstractMatcherTest
{
    public static function provideRealXmlCases(): Generator
    {
        yield 'sequential' => [
            nested(
                document_element(),
                element_name('users'),
                all(
                    element_name('user'),
                    attribute_value('locale', 'nl')
                )
            ),
            <<<'EOXML'
            <root>
                <users>
                    <user locale="nl">Jos</user>
                    <user>Bos</user>
                    <user>Mos</user>
                </users>
            </root>
            EOXML,
            [
                '<user locale="nl">Jos</user>',
            ]
        ];
        yield 'skipped-items' => [
            nested(
                element_name('root'),
                element_name('user'),
            ),
            <<<'EOXML'
            <root>
                <users>
                    <user locale="nl">Jos</user>
                    <user>Bos</user>
                    <user>Mos</user>
                </users>
            </root>
            EOXML,
            [
                '<user locale="nl">Jos</user>',
                '<user>Bos</user>',
                '<user>Mos</user>',
            ]
        ];
        yield 'similar-paths' => [
            nested(
                element_name('users'),
                element_name('user'),
            ),
            <<<'EOXML'
            <root>
                <users>
                    <user>Jos</user>
                    <user>Bos</user>
                </users>
                <admins>
                    <user>Mos</user>
                </admins>
            </root>
            EOXML,
            [
                '<user>Jos</user>',
                '<user>Bos</user>',
            ]
        ];
        yield 'combined-with-sequence' => [
            nested(
                document_element(),
                sequence(
                    element_name('users'),
                    element_name('user'),
                ),
            ),
            <<<'EOXML'
            <root>
                <users>
                    <user>Jos</user>
                    <user>Bos</user>
                </users>
            </root>
            EOXML,
            [
                '<user>Jos</user>',
                '<user>Bos</user>',
            ]
        ];
        yield 'multi-parent-items' => [
            nested(
                element_name('root'),
                element_name('user'),
            ),
            <<<'EOXML'
            <root>
                <users>
                    <user>Jos</user>
                    <user>Bos</user>
                    
                </users>
                <admins>
                    <user>Mos</user>
                </admins>
            </root>
            EOXML,
            [
                '<user>Jos</user>',
                '<user>Bos</user>',
                '<user>Mos</user>',
            ]
        ];
        yield 'in-between-match' => [
            nested(
                element_name('root'),
                element_name('users'),
            ),
            <<<'EOXML'
            <root>
                <users>
                    <user>Jos</user>
                    <user>Bos</user>
                </users>
                <admins>
                    <user>Mos</user>
                </admins>
            </root>
            EOXML,
            [
                <<<'EOXML'
                <users>
                        <user>Jos</user>
                        <user>Bos</user>
                    </users>
                EOXML,
            ]
        ];
        yield 'deeply-nested-sequence' => [
            nested(
                document_element(),
                sequence(element_name('users')),
                sequence(element_name('user')),
            ),
            <<<'EOXML'
            <root>
                <users>
                    <user>Jos</user>
                    <user>Bos</user>
                </users>
            </root>
            EOXML,
            [
                '<user>Jos</user>',
                '<user>Bos</user>',
            ]
        ];
    }

    public static function provideMatcherCases(): Generator
    {
        yield 'it_returns_false_if_no_matcher' => [
            nested(),
            new NodeSequence(
                new ElementNode(1, 'root', 'root', '', '', []),
            ),
            false
        ];

        yield 'it_returns_false_if_no_sequence' => [
            nested(document_element()),
            new NodeSequence(),
            false
        ];

        yield 'it_returns_false_if_there_are_no_matchers_left_before_the_end_of_the_node_sequence' => [
            nested(document_element()),
            new NodeSequence(
                new ElementNode(1, 'root', 'root', '', '', []),
                new ElementNode(1, 'users', 'users', '', '', []),
            ),
            false
        ];

        yield 'it_returns_false_if_there_are_still_matchers_left_at_the_end_of_the_node_sequence' => [
            nested(element_name('notfound')),
            new NodeSequence(
                new ElementNode(1, 'root', 'root', '', '', []),
                new ElementNode(1, 'users', 'users', '', '', []),
            ),
            false
        ];

        yield 'it_returns_true_if_the_last_matcher_hits_the_end_of_the_node_sequence' => [
            nested(element_name('root'), element_name('users')),
            new NodeSequence(
                new ElementNode(1, 'root', 'root', '', '', []),
                new ElementNode(1, 'users', 'users', '', '', []),
            ),
            true
        ];

        yield 'it_can_skip_nodes_looking_for_the_next_one' => [
            nested(element_name('root'), element_name('user')),
            new NodeSequence(
                new ElementNode(1, 'root', 'root', '', '', []),
                new ElementNode(1, 'users', 'users', '', '', []),
                new ElementNode(1, 'user', 'user', '', '', []),
            ),
            true
        ];

        yield 'it_uses_a_sliced_node_sequence_breakpoint_on_match' => [
            nested(
                element_name('root'),
                sequence(
                    element_name('users'),
                    element_name('user')
                )
            ),
            new NodeSequence(
                new ElementNode(1, 'root', 'root', '', '', []),
                new ElementNode(1, 'users', 'users', '', '', []),
                new ElementNode(1, 'user', 'user', '', '', []),
            ),
            true
        ];
    }
}
