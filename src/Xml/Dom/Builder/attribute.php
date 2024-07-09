<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use Closure;
use \Dom\Element;

/**
 * @return Closure(\Dom\Element): \Dom\Element
 */
function attribute(string $name, string $value): Closure
{
    return static function (\Dom\Element $node) use ($name, $value): \Dom\Element {
        $node->setAttribute($name, $value);

        return $node;
    };
}
