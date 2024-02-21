<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding\Internal\Decoder\Builder;

use \DOM\Element;
use function Psl\Dict\map;
use function Psl\Dict\merge;
use function Psl\Iter\reduce_with_keys;

/**
 * @psalm-internal VeeWee\Xml\Encoding
 *
 */
function grouped_children(\DOM\Element $element): array
{
    return reduce_with_keys(
        group_child_elements($element),
        /**
         * @param array $children
         * @param \DOM\Element|list<\DOM\Element> $child
         * @return array
         */
        static fn (array $children, string $name, \DOM\Element|array $child): array
            => merge(
                $children,
                [
                    $name => is_array($child)
                        ? [...map($child, static fn (\DOM\Element $child): array|string
                            => unwrap_element(element($child)))]
                        : unwrap_element(element($child))
                ]
            ),
        [],
    );
}
