<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding\Internal\Decoder\Builder;

use DOMElement;
use function Psl\Dict\filter;
use function Psl\Dict\merge;

/**
 * @psalm-internal VeeWee\Xml\Encoding
 * @return array<string, string|array>
 */
function element(DOMElement $element): array
{
    $name = name($element);
    $children = grouped_children($element);
    $attributes = attributes($element);
    $namespaces = namespaces($element);

    if (!count($children) && !count($attributes) && !count($namespaces)) {
        return [$name => $element->textContent];
    }

    return [
        $name => filter(
            merge(
                $namespaces,
                $attributes,
                $children ?: ['@value' => $element->textContent]
            )
        )
    ];
}
