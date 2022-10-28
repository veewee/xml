<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader\Matcher;

use Closure;
use Psl\Iter;
use VeeWee\Xml\Reader\Node\NodeSequence;

/**
 * @param list<\Closure(NodeSequence): bool> $matchers
 *
 * @return \Closure(NodeSequence): bool
 */
function all(Closure ... $matchers): Closure
{
    return static fn (NodeSequence $sequence): bool => Iter\all(
        $matchers,
        /**
         * @param \Closure(NodeSequence): bool $matcher
         */
        static fn (Closure $matcher): bool => $matcher($sequence)
    );
}
