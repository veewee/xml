<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding\Internal\Decoder\Builder;

use \DOM\Attr;
use \DOM\Element;
use function Psl\Dict\filter;
use function Psl\Dict\merge;
use function Psl\Iter\reduce;

/**
 * @psalm-internal VeeWee\Xml\Encoding
 */
function attributes(\DOM\Element $element): array
{
    return filter([
        '@attributes' => reduce(
            $element->attributes,
            static fn (array $attributes, \DOM\Attr $attr): array
                => merge($attributes, attribute($attr)),
            []
        )
    ]);
}
