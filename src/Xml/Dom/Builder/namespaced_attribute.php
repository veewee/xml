<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use DOMElement;
use function VeeWee\Xml\Assertion\assert_strict_qualified_name;

/**
 * @return callable(DOMElement): DOMElement
 */
function namespaced_attribute(string $namespace, string $qualifiedName, string $value): callable {
    return static function (DOMElement $node) use ($namespace, $qualifiedName, $value): DOMElement {
        assert_strict_qualified_name($qualifiedName);

        $node->setAttributeNS($namespace, $qualifiedName, $value);

        return $node;
    };
}
