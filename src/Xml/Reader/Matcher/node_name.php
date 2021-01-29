<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader\Matcher;

use VeeWee\Xml\Reader\Node\NodeSequence;

/**
 * @return callable(NodeSequence): bool
 */
function node_name(string $name): callable
{
    return static function (NodeSequence $sequence) use ($name): bool {
        return $sequence->current()->name() === $name;
    };
}
