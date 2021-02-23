<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use DOMElement;

/**
 * @return callable(DOMElement): DOMElement
 */
function escaped_value(string $value): callable
{
    return static function (DOMElement $node) use ($value): DOMElement {
        $node->nodeValue = htmlspecialchars($value);

        return $node;
    };
}
