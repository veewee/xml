<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding\Internal\Decoder\Builder;

use \DOM\Attr;

/**
 * @psalm-internal VeeWee\Xml\Encoding
 */
function attribute(\DOM\Attr $attribute): array
{
    return [
        name($attribute) => $attribute->nodeValue,
    ];
}
