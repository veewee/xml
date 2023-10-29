<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Reader\Matcher;

use Generator;
use VeeWee\Xml\Reader\Node\NodeSequence;
use function VeeWee\Xml\Reader\Matcher\any;
use function VeeWee\Xml\Reader\Matcher\element_name;

final class AnyTest extends AbstractMatcherTest
{
    public static function provideRealXmlCases(): Generator
    {
        yield 'any' => [
            any(),
            <<<'EOXML'
            <root>
                <user>Jos</user>
                <user>Bos</user>
                <user>Mos</user>
            </root>
            EOXML,
            []
        ];
        yield 'users' => [
            any(element_name('user')),
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
    }

    public static function provideMatcherCases(): Generator
    {
        $sequence = new NodeSequence();

        yield 'it_returns_true_if_all_matchers_agree' => [
            any(
                static fn () => true,
                static fn () => true,
                static fn () => true
            ),
            $sequence,
            true
        ];

        yield 'it_returns_true_if_any_matchers_agree' => [
            any(
                static fn () => false,
                static fn () => true,
                static fn () => false
            ),
            $sequence,
            true
        ];

        yield 'it_returns_false_if_no_matchers_agree' => [
            any(
                static fn () => false,
                static fn () => false,
                static fn () => false
            ),
            $sequence,
            false
        ];

        yield 'it_returns_false_if_there_are_no_matchers' => [
            any(),
            $sequence,
            false
        ];
    }
}
