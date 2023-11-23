<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader\Matcher;

use Closure;
use VeeWee\Xml\Reader\Node\NodeSequence;

/**
 * @return Closure(NodeSequence): bool
 */
function element_name(string $name): Closure
{
    return static function (NodeSequence $sequence) use ($name): bool {
        return $sequence->current()->name() === $name;
    };
}
