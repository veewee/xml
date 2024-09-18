<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding\Internal\Decoder\Builder;

use Dom\Attr;
use Dom\Element;
use function Psl\Dict\filter;
use function Psl\Dict\merge;
use function Psl\Iter\reduce;
use function VeeWee\Xml\Dom\Locator\Attribute\attributes_list;
use function VeeWee\Xml\Dom\Predicate\is_xmlns_attribute;

/**
 * @psalm-internal VeeWee\Xml\Encoding
 */
function attributes(Element $element): array
{
    return filter([
        '@attributes' => reduce(
            attributes_list($element)->filter(static fn (Attr $attr): bool => !is_xmlns_attribute($attr)),
            static fn (array $attributes, Attr $attr): array
                => merge($attributes, attribute($attr)),
            []
        )
    ]);
}
