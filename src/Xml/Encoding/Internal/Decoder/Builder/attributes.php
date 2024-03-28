<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding\Internal\Decoder\Builder;

use \DOM\Attr;
use \DOM\Element;
use function Psl\Dict\filter;
use function Psl\Dict\merge;
use function Psl\Iter\reduce;
use function VeeWee\Xml\Dom\Locator\Attribute\attributes_list;
use function VeeWee\Xml\Dom\Predicate\is_xmlns_attribute;

/**
 * @psalm-internal VeeWee\Xml\Encoding
 */
function attributes(\DOM\Element $element): array
{
    return filter([
        '@attributes' => reduce(
            attributes_list($element)->filter(static fn(\DOM\Attr $attr): bool => !is_xmlns_attribute($attr)),
            static fn (array $attributes, \DOM\Attr $attr): array
                => merge($attributes, attribute($attr)),
            []
        )
    ]);
}
