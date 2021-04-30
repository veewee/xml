<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding\Internal\Decoder\Builder;

use DOMElement;
use DOMNameSpaceNode;
use function Psl\Dict\filter;
use function Psl\Dict\merge;
use function VeeWee\Xml\Dom\Locator\Attributes\xmlns_attributes_list;

/**
 * @psalm-internal VeeWee\Xml\Encoding
 */
function namespaces(DOMElement $element): array
{
    return filter([
        '@namespaces' => xmlns_attributes_list($element)->reduce(
            static fn (array $namespaces, DOMNameSpaceNode $node)
                => merge($namespaces, [(string) $node->prefix => $node->namespaceURI]),
            []
        ),
    ]);
}
