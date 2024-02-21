<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use Closure;
use \DOM\Element;

/**
 * @return Closure(\DOM\Element): \DOM\Element
 */
function attribute(string $name, string $value): Closure
{
    return static function (\DOM\Element $node) use ($name, $value): \DOM\Element {
        $node->setAttribute($name, $value);

        return $node;
    };
}
