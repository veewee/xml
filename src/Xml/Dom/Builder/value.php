<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use Closure;
use \DOM\Element;

/**
 * @return Closure(\DOM\Element): \DOM\Element
 */
function value(string $value): Closure
{
    return static function (\DOM\Element $node) use ($value): \DOM\Element {
        $node->substitutedNodeValue = $value;

        return $node;
    };
}
