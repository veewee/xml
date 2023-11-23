<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader\Matcher;

use Closure;
use VeeWee\Xml\Reader\Node\NodeSequence;
use function count;

/**
 * A sequence can be used to match a full XML path to the node you are interested in.
 *
 * ```php
 * sequence(element_name('root'), all(element_name('item'), element_position(1)), element_name('name'));
 * ```
 *
 * @param non-empty-list<callable(NodeSequence): bool> $matcherSequence
 *
 * @return Closure(NodeSequence): bool
 */
function sequence(callable ... $matcherSequence): Closure
{
    return static function (NodeSequence $sequence) use ($matcherSequence) : bool {
        if (count($matcherSequence) !== $sequence->count()) {
            return false;
        }

        foreach ($sequence->replay() as $index => $currentSequence) {
            $matcher = $matcherSequence[$index];
            if (!$matcher($currentSequence)) {
                return false;
            }
        }

        return true;
    };
}
