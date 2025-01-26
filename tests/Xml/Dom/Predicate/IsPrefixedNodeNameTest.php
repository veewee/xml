<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Predicate;

use PHPUnit\Framework\TestCase;
use function VeeWee\Xml\Dom\Predicate\is_prefixed_node_name;

final class IsPrefixedNodeNameTest extends TestCase
{
    /**
     *
     * @dataProvider provideValidQNames
     */
    public function test_it_does_nothing_on_valid_qnames(string $input): void
    {
        static::assertTrue(is_prefixed_node_name($input));
    }

    /**
     *
     * @dataProvider provideInvalidQNames
     */
    public function test_it_throws_on_invalid_qnames(string $input): void
    {
        static::assertFalse(is_prefixed_node_name($input));
    }

    public static function provideValidQNames()
    {
        yield ['hello:world'];
        yield ['a:b'];
        yield ['---a----:----b---'];
    }

    public static function provideInvalidQNames()
    {
        yield [''];
        yield ['aa'];
        yield ['aa:'];
        yield [':bb'];
        yield [':b:c:cd:dz'];
    }
}
