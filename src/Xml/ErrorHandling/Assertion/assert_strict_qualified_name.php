<?php

declare(strict_types=1);

namespace VeeWee\Xml\Assertion;

use InvalidArgumentException;
use Webmozart\Assert\Assert;

/**
 * @psalm-pure
 *
 * @param string $qualifiedName
 *
 * @throws InvalidArgumentException
 */
function assert_strict_qualified_name(string $qualifiedName): void
{
    Assert::regex(
        $qualifiedName,
        '/^[^:]+:[^:]+$/',
        'The provided value %1$s is not a QName, expected ns:name instead.'
    );
}
