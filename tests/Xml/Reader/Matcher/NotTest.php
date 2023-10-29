<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Reader\Matcher;

use Generator;
use VeeWee\Xml\Reader\Node\NodeSequence;
use function VeeWee\Xml\Reader\Matcher\element_name;
use function VeeWee\Xml\Reader\Matcher\not;

final class NotTest extends AbstractMatcherTest
{
    public static function provideRealXmlCases(): Generator
    {
        yield 'users' => [
            not(element_name('root')),
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

        yield 'it_returns_true_if_the_inner_matcher_returns_false' => [
            not(
                static fn () => false,
            ),
            $sequence,
            true
        ];

        yield 'it_returns_false_if_the_inner_matcher_returns_true' => [
            not(
                static fn () => true,
            ),
            $sequence,
            false
        ];
    }
}
