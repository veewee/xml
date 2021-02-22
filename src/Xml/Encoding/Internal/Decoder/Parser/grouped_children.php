<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding\Internal\Decoder\Parser;

use DOMElement;
use VeeWee\Xml\Encoding\Internal\Decoder\Context;
use function Psl\Dict\filter;
use function Psl\Dict\map;
use function Psl\Iter\first;
use function Psl\Iter\reduce_with_keys;

/**
 * @psalm-internal VeeWee\Xml\Encoding
 */
function grouped_children(DOMElement $element, Context $context): array
{
    return filter(
        reduce_with_keys(
            group_child_elements($element),
            static fn (array $children, string $name, DOMElement|array $child): array
                => array_merge(
                    $children,
                    [
                        $name => is_array($child)
                            ? [...map($child, static fn (DOMElement $child): array => first(element($child, $context)))]
                            : first(element($child, $context))
                    ]
                ),
            [],
        )
    );
}
