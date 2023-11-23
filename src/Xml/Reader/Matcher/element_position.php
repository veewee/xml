<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader\Matcher;

use Closure;
use VeeWee\Xml\Reader\Node\NodeSequence;

/**
 * @return Closure(NodeSequence): bool
 */
function element_position(int $position): Closure
{
    return static function (NodeSequence $sequence) use ($position): bool {
        return $sequence->current()->position() === $position;
    };
}
