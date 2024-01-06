<?php
declare(strict_types=1);

namespace VeeWee\Tests\Xml\Reader\Matcher;

use Closure;
use Generator;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Reader\MatchingNode;
use VeeWee\Xml\Reader\Node\NodeSequence;
use VeeWee\Xml\Reader\Reader;
use function Psl\Vec\map;

abstract class AbstractMatcherTest extends TestCase
{
    abstract public static function provideRealXmlCases(): Generator;
    abstract public static function provideMatcherCases(): Generator;

    /**
     * @dataProvider provideRealXmlCases
     *
     * @param Closure(NodeSequence): bool $matcher
     * @param list<string> $expected
     */
    public function test_real_xml_cases(Closure $matcher, string $xml, array $expected)
    {
        $reader = Reader::fromXmlString($xml);
        $actual = map($reader->provide($matcher), static fn (MatchingNode $match): string => $match->xml());

        static::assertSame($actual, $expected);
    }

    /**
     * @dataProvider provideMatcherCases
     *
     * @param Closure(NodeSequence): bool $matcher
     */
    public function test_matcher_cases(Closure $matcher, NodeSequence $sequence, bool $expected)
    {
        $actual = $matcher($sequence);

        static::assertSame($actual, $expected);
    }
}
