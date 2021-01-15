<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use DOMElement;

/**
 * @return callable(DOMElement): DOMElement
 */
function value(string $value): callable {
    return static function (DOMElement $node) use ($value): DOMElement {
        $node->nodeValue = $value;

        return $node;
    };
}
