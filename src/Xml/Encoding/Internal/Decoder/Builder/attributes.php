<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding\Internal\Decoder\Builder;

use DOMAttr;
use DOMElement;
use function Psl\Dict\filter;
use function Psl\Dict\merge;
use function Psl\Iter\reduce;

/**
 * @psalm-internal VeeWee\Xml\Encoding
 */
function attributes(DOMElement $element): array
{
    return filter([
        '@attributes' => reduce(
            $element->attributes,
            static fn (array $attributes, DOMAttr $attr): array
                => merge($attributes, attribute($attr)),
            []
        )
    ]);
}
