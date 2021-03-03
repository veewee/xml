<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding\Internal\Encoder\Builder;

use JsonSerializable;
use Psl\Type\Exception\CoercionException;
use VeeWee\Xml\Encoding\XmlSerializable;
use function Psl\Dict\map;
use function Psl\Type\string;

/**
 * @psalm-internal VeeWee\Xml\Encoding
 *
 * @template T
 * @param T $data
 *
 * @return (T is iterable ? array : string)
 *
 * @throws CoercionException
 */
function normalize_data(mixed $data): array|string
{
    if ($data instanceof XmlSerializable) {
        return normalize_data($data->xmlSerialize());
    }

    if (is_iterable($data)) {
        return map(
            $data,
            static fn (mixed $value): array|string => normalize_data($value)
        );
    }

    if ($data instanceof JsonSerializable) {
        return normalize_data($data->jsonSerialize());
    }

    return string()->coerce($data);
}
