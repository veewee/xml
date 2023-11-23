<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader\Matcher;

use Closure;
use VeeWee\Xml\Reader\Node\NodeSequence;

/**
 * @param callable(NodeSequence): bool $matcher
 *
 * @return Closure(NodeSequence): bool
 */
function not(callable $matcher): Closure
{
    return static fn (NodeSequence $sequence): bool => !$matcher($sequence);
}
