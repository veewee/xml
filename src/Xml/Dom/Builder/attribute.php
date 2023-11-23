<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use Closure;
use DOMElement;

/**
 * @return Closure(DOMElement): DOMElement
 */
function attribute(string $name, string $value): Closure
{
    return static function (DOMElement $node) use ($name, $value): DOMElement {
        $node->setAttribute($name, $value);

        return $node;
    };
}
