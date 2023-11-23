<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader\Matcher;

use Closure;
use Psl\Iter;
use VeeWee\Xml\Reader\Node\NodeSequence;

/**
 * @param list<callable(NodeSequence): bool> $matchers
 *
 * @return Closure(NodeSequence): bool
 */
function all(callable ... $matchers): Closure
{
    return static fn (NodeSequence $sequence): bool => Iter\all(
        $matchers,
        /**
         * @param callable(NodeSequence): bool $matcher
         */
        static fn (callable $matcher): bool => $matcher($sequence)
    );
}
