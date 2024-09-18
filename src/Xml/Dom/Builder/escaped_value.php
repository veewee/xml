<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use Closure;
use Dom\Element;

/**
 * @return Closure(Element): Element
 */
function escaped_value(string $value): Closure
{
    return static function (Element $node) use ($value): Element {
        return value(htmlspecialchars($value, ENT_XML1|ENT_QUOTES))($node);
    };
}
