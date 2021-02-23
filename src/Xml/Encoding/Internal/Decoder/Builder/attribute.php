<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding\Internal\Decoder\Builder;

use DOMAttr;

/**
 * @psalm-internal VeeWee\Xml\Encoding
 */
function attribute(DOMAttr $attribute): array
{
    return [
        name($attribute) => $attribute->nodeValue,
    ];
}
