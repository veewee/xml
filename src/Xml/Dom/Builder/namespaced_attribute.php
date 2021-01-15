<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use DOMElement;

/**
 * @return callable(DOMElement): DOMElement
 */
function namespaced_attribute(string $namespace, string $name, string $value): callable {
    return static function (DOMElement $node) use ($namespace, $name, $value): DOMElement {
        $node->setAttributeNS($namespace, $name, $value);

        return $node;
    };
}
