<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use Closure;

/**
 * @return Closure(\DOM\Element): \DOM\Element
 */
function escaped_value(string $value): Closure
{
    return static function (\DOM\Element $node) use ($value): \DOM\Element {
        return value(htmlspecialchars($value, ENT_XML1|ENT_QUOTES))($node);
    };
}
