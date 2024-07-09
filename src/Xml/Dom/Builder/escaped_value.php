<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use Closure;

/**
 * @return Closure(\Dom\Element): \Dom\Element
 */
function escaped_value(string $value): Closure
{
    return static function (\Dom\Element $node) use ($value): \Dom\Element {
        return value(htmlspecialchars($value, ENT_XML1|ENT_QUOTES))($node);
    };
}
