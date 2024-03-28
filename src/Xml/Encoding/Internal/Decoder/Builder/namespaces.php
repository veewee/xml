<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding\Internal\Decoder\Builder;

use \DOM\Element;
use VeeWee\Xml\Exception\RuntimeException;
use function Psl\Dict\filter;
use function Psl\Dict\merge;
use function VeeWee\Xml\Dom\Locator\Attribute\xmlns_attributes_list;

/**
 * @psalm-internal VeeWee\Xml\Encoding
 * @psalm-suppress RedundantCast
 * @throws RuntimeException
 */
function namespaces(\DOM\Element $element): array
{
    return filter([
        '@namespaces' => xmlns_attributes_list($element)->reduce(
            static fn (array $namespaces, \DOM\Attr $node)
                => $node->value
                    ? merge($namespaces, [
                        ($node->prefix ? $node->localName : '') => $node->value
                    ])
                    : $namespaces,
            []
        ),
    ]);
}
