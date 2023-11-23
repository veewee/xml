<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader\Matcher;

use Closure;
use VeeWee\Xml\Reader\Node\NodeSequence;

/**
 * @return Closure(NodeSequence): bool
 */
function element_local_name(string $localName): Closure
{
    return static function (NodeSequence $sequence) use ($localName): bool {
        return $sequence->current()->localName() === $localName;
    };
}
