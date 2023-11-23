<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader\Matcher;

use Closure;
use VeeWee\Xml\Reader\Node\NodeSequence;

/**
 * @return Closure(NodeSequence): bool
 */
function namespaced_element(string $namespace, string $localName): Closure
{
    return static function (NodeSequence $sequence) use ($namespace, $localName): bool {
        $current = $sequence->current();

        return $current->localName() === $localName && $current->namespace() === $namespace;
    };
}
