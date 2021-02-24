<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding\Internal\Decoder\Builder;

use DOMElement;
use VeeWee\Xml\Encoding\Internal\Decoder\Context;
use function Psl\Dict\filter;
use function Psl\Dict\map;
use function Psl\Dict\merge;
use function Psl\Iter\first;
use function Psl\Iter\reduce_with_keys;

/**
 * @psalm-internal VeeWee\Xml\Encoding
 *
 * @return array<string, array|string>
 */
function grouped_children(DOMElement $element, Context $context): array
{
    return filter(
        reduce_with_keys(
            group_child_elements($element),
            /**
             * @param DOMElement|list<DOMElement> $child
             * @param array<string, array|string> $children
             * @return array<string, array|string>
             */
            static fn (array $children, string $name, DOMElement|array $child): array
                => merge(
                    $children,
                    [
                        $name => is_array($child)
                            ? [...map($child, static fn (DOMElement $child): array|string => first(element($child, $context)))]
                            : first(element($child, $context))
                    ]
                ),
            [],
        )
    );
}
