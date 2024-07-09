<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use Closure;
use \Dom\Element;
use function Psl\Iter\reduce_with_keys;

/**
 * @param array<string, string> $attributes
 * @return Closure(\Dom\Element): \Dom\Element
 */
function namespaced_attributes(string $namespace, array $attributes): Closure
{
    return static function (\Dom\Element $node) use ($namespace, $attributes): \Dom\Element {
        return reduce_with_keys(
            $attributes,
            static fn (\Dom\Element $node, string $name, string $value)
                => namespaced_attribute($namespace, $name, $value)($node),
            $node
        );
    };
}
