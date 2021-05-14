<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use DOMElement;
use function Psl\Iter\reduce_with_keys;

/**
 * @param array<non-empty-string, non-empty-string> $attributes - A map of namespace prefix with namespace URI
 * @return callable(DOMElement): DOMElement
 */
function xmlns_attributes(array $attributes): callable
{
    return static function (DOMElement $node) use ($attributes): DOMElement {
        return reduce_with_keys(
            $attributes,
            static fn (DOMElement $node, string $name, string $value)
                => xmlns_attribute($name, $value)($node),
            $node
        );
    };
}
