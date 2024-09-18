<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Reader\Matcher;

use Generator;
use VeeWee\Xml\Reader\Node\NodeSequence;
use function VeeWee\Xml\Reader\Matcher\all;
use function VeeWee\Xml\Reader\Matcher\element_name;

final class AllTest extends AbstractMatcherTester
{
    public static function provideRealXmlCases(): Generator
    {
        yield 'all' => [
            all(),
            $xml = <<<'EOXML'
            <root>
                <user>Jos</user>
                <user>Bos</user>
                <user>Mos</user>
            </root>
            EOXML,
            [
                $xml,
                '<user>Jos</user>',
                '<user>Bos</user>',
                '<user>Mos</user>'
            ]
        ];
        yield 'users' => [
            all(element_name('user')),
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
            all(
                static fn () => true,
                static fn () => true,
                static fn () => true
            ),
            $sequence,
            true
        ];

        yield 'it_returns_false_if_not_all_matchers_agree' => [
            all(
                static fn () => true,
                static fn () => true,
                static fn () => false
            ),
            $sequence,
            false
        ];

        yield 'it_returns_true_if_there_are_no_matchers' => [
            all(),
            $sequence,
            true
        ];
    }
}
