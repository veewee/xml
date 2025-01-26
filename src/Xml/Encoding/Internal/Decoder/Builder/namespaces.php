<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding\Internal\Decoder\Builder;

use Dom\Attr;
use Dom\Element;
use VeeWee\Xml\Exception\RuntimeException;
use function Psl\Dict\filter;
use function Psl\Dict\merge;
use function VeeWee\Xml\Dom\Locator\Attribute\xmlns_attributes_list;

/**
 * @psalm-internal VeeWee\Xml\Encoding
 * @psalm-suppress RedundantCast
 * @throws RuntimeException
 */
function namespaces(Element $element): array
{
    return filter([
        '@namespaces' => xmlns_attributes_list($element)->reduce(
            static fn (array $namespaces, Attr $node) => merge($namespaces, [
                ($node->prefix !== null ? $node->localName : '') => $node->value
            ]),
            []
        ),
    ]);
}
