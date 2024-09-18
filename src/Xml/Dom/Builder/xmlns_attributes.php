<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use Closure;
use Dom\Element;
use function Psl\Iter\reduce_with_keys;

/**
 * @param array<string, string> $attributes - A map of namespace prefix with namespace URI
 * @return Closure(Element): Element
 */
function xmlns_attributes(array $attributes): Closure
{
    return static function (Element $node) use ($attributes): Element {
        return reduce_with_keys(
            $attributes,
            static fn (Element $node, string $name, string $value)
                => xmlns_attribute($name, $value)($node),
            $node
        );
    };
}
