<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding\Internal\Decoder\Builder;

use DOMElement;
use VeeWee\Xml\Encoding\Internal\Decoder\Context;
use function Psl\Dict\filter;
use function Psl\Dict\merge;
use function Psl\Iter\reduce;
use function Psl\Vec\keys;

/**
 * @psalm-internal VeeWee\Xml\Encoding
 */
function namespaces(DOMElement $element, Context $context): array
{
    return filter([
        '@namespaces' => reduce(
            ['', ...keys($context->knownNamespaces())],
            static function (array $namespaces, string $prefix) use ($element): array {
                $attrName = join(':', filter(['xmlns', $prefix]));

                if (!$element->hasAttribute($attrName)) {
                    return $namespaces;
                }

                return merge($namespaces, [$prefix => $element->getAttribute($attrName)]);
            },
            []
        ),
    ]);
}
