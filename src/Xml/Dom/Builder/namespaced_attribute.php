<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use Closure;
use DOMElement;
use function VeeWee\Xml\Assertion\assert_strict_prefixed_name;

/**
 * @return Closure(DOMElement): DOMElement
 */
function namespaced_attribute(string $namespace, string $qualifiedName, string $value): Closure
{
    return static function (DOMElement $node) use ($namespace, $qualifiedName, $value): DOMElement {
        assert_strict_prefixed_name($qualifiedName);

        $node->setAttributeNS($namespace, $qualifiedName, $value);

        return $node;
    };
}
