<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding\Internal\Decoder\Builder;

use function Psl\Iter\first;

/**
 * @psalm-internal VeeWee\Xml\Encoding
 *
 * @param array<array-key, string|array> $element
 */
function unwrap_element(array $element): array|string
{
    return first($element) ?? '*UNKNOWN*';
}
