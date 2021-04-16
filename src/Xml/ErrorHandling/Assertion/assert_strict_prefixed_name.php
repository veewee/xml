<?php

declare(strict_types=1);

namespace VeeWee\Xml\Assertion;

use InvalidArgumentException;
use Webmozart\Assert\Assert;

/**
 * @throws InvalidArgumentException
 */
function assert_strict_prefixed_name(string $qualifiedName): void
{
    Assert::regex(
        $qualifiedName,
        '/^[^:]+:[^:]+$/',
        'The provided value %1$s is not a QName, expected ns:name instead.'
    );
}
