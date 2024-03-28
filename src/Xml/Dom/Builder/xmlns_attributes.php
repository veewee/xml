<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use Closure;
use \DOM\Element;
use function Psl\Iter\reduce_with_keys;

/**
 * @param array<string, string> $attributes - A map of namespace prefix with namespace URI
 * @return Closure(\DOM\Element): \DOM\Element
 */
function xmlns_attributes(array $attributes): Closure
{
    return static function (\DOM\Element $node) use ($attributes): \DOM\Element {
        return reduce_with_keys(
            $attributes,
            static fn (\DOM\Element $node, string $name, string $value)
                => xmlns_attribute($name, $value)($node),
            $node
        );
    };
}
