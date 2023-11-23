<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use Closure;
use DOMElement;
use function Psl\Iter\reduce_with_keys;

/**
 * @param array<string, string> $attributes
 * @return Closure(DOMElement): DOMElement
 */
function attributes(array $attributes): Closure
{
    return static function (DOMElement $node) use ($attributes): DOMElement {
        return reduce_with_keys(
            $attributes,
            static fn (DOMElement $node, string $name, string $value)
                => attribute($name, $value)($node),
            $node
        );
    };
}
