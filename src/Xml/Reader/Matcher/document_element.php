<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader\Matcher;

use Closure;
use VeeWee\Xml\Reader\Node\NodeSequence;

/**
 * @return Closure(NodeSequence): bool
 */
function document_element(): Closure
{
    return static function (NodeSequence $sequence): bool {
        return !$sequence->parent();
    };
}
