<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use Closure;
use Dom\Element;

/**
 * @return Closure(Element): Element
 */
function value(string $value): Closure
{
    return static function (Element $node) use ($value): Element {
        $node->substitutedNodeValue = $value;

        return $node;
    };
}
