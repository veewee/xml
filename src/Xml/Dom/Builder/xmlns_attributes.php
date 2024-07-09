<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use Closure;
use \Dom\Element;
use function Psl\Iter\reduce_with_keys;

/**
 * @param array<string, string> $attributes - A map of namespace prefix with namespace URI
 * @return Closure(\Dom\Element): \Dom\Element
 */
function xmlns_attributes(array $attributes): Closure
{
    return static function (\Dom\Element $node) use ($attributes): \Dom\Element {
        return reduce_with_keys(
            $attributes,
            static fn (\Dom\Element $node, string $name, string $value)
                => xmlns_attribute($name, $value)($node),
            $node
        );
    };
}
