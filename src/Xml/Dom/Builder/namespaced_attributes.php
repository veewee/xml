<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use Closure;
use \DOM\Element;
use function Psl\Iter\reduce_with_keys;

/**
 * @param array<string, string> $attributes
 * @return Closure(\DOM\Element): \DOM\Element
 */
function namespaced_attributes(string $namespace, array $attributes): Closure
{
    return static function (\DOM\Element $node) use ($namespace, $attributes): \DOM\Element {
        return reduce_with_keys(
            $attributes,
            static fn (\DOM\Element $node, string $name, string $value)
                => namespaced_attribute($namespace, $name, $value)($node),
            $node
        );
    };
}
