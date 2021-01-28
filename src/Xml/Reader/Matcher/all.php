<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader\Matcher;

use Psl\Iter;
use VeeWee\Xml\Reader\Node\NodeSequence;

/**
 * @param list<callable(NodeSequence): bool)> $matchers
 *
 * @return callable(NodeSequence): bool
 */
function all(callable ... $matchers): callable
{
    return static fn (NodeSequence $sequence): bool => Iter\all(
        $matchers,
        static fn (callable $matcher): bool => $matcher($sequence)
    );
}
