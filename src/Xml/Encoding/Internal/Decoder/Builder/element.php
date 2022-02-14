<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding\Internal\Decoder\Builder;

use DOMElement;
use VeeWee\Xml\Exception\RuntimeException;
use function Psl\Dict\filter;
use function Psl\Dict\merge;

/**
 * @psalm-internal VeeWee\Xml\Encoding
 * @return array<string, string|array>
 * @throws RuntimeException
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
            ),
            static fn (mixed $data): bool => $data !== []
        )
    ];
}
