<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use Closure;
use \Dom\Element;

/**
 * @return Closure(\Dom\Element): \Dom\Element
 */
function value(string $value): Closure
{
    return static function (\Dom\Element $node) use ($value): \Dom\Element {
        $node->substitutedNodeValue = $value;

        return $node;
    };
}
