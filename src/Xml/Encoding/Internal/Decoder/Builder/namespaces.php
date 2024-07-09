<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding\Internal\Decoder\Builder;

use \Dom\Element;
use VeeWee\Xml\Exception\RuntimeException;
use function Psl\Dict\filter;
use function Psl\Dict\merge;
use function VeeWee\Xml\Dom\Locator\Attribute\xmlns_attributes_list;

/**
 * @psalm-internal VeeWee\Xml\Encoding
 * @psalm-suppress RedundantCast
 * @throws RuntimeException
 */
function namespaces(\Dom\Element $element): array
{
    return filter([
        '@namespaces' => xmlns_attributes_list($element)->reduce(
            static fn (array $namespaces, \Dom\Attr $node)
                => $node->value
                    ? merge($namespaces, [
                        ($node->prefix !== null ? $node->localName : '') => $node->value
                    ])
                    : $namespaces,
            []
        ),
    ]);
}
