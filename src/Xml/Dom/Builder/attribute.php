<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use DOMElement;

/**
 * @return callable(DOMElement): DOMElement
 */
function attribute(string $name, string $value): callable {
    return static function (DOMElement $node) use ($name, $value): DOMElement {
        $node->setAttribute($name, $value);

        return $node;
    };
}
