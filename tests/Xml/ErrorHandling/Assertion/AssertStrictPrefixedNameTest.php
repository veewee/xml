<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\ErrorHandling\Assertion;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use function VeeWee\Xml\Assertion\assert_strict_prefixed_name;

final class AssertStrictPrefixedNameTest extends TestCase
{
    /**
     *
     * @dataProvider provideValidQNames
     */
    public function testIt_does_nothing_on_valid_qnames(string $input): void
    {
        $this->expectNotToPerformAssertions();
        assert_strict_prefixed_name($input);
    }

    /**
     *
     * @dataProvider provideInvalidQNames
     */
    public function testIt_throws_on_invalid_qnames(string $input): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectErrorMessage('The provided value "'.$input.'" is not a QName, expected ns:name instead.');

        assert_strict_prefixed_name($input);
    }

    public function provideValidQNames()
    {
        yield ['hello:world'];
        yield ['a:b'];
        yield ['---a----:----b---'];
    }

    public function provideInvalidQNames()
    {
        yield [''];
        yield ['aa'];
        yield ['aa:'];
        yield [':bb'];
        yield [':b:c:cd:dz'];
    }
}
