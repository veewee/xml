<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use Closure;
use Dom\Element;

/**
 * @return Closure(Element): Element
 */
function attribute(string $name, string $value): Closure
{
    return static function (Element $node) use ($name, $value): Element {
        $node->setAttribute($name, $value);

        return $node;
    };
}
