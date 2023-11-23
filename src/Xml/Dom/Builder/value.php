<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use Closure;
use DOMElement;

/**
 * @return Closure(DOMElement): DOMElement
 */
function value(string $value): Closure
{
    return static function (DOMElement $node) use ($value): DOMElement {
        $node->nodeValue = $value;

        return $node;
    };
}
