<?php

declare(strict_types=1);

namespace VeeWee\Xml\ErrorHandling;

use VeeWee\Xml\Exception\RuntimeException;

/**
 * @template T
 *
 * @param T $value
 *
 * @psalm-ignore-falsable-return
 * @return T
 *
 * @psalm-assert !false $value
 * @throws RuntimeException
 */
function disallow_libxml_false_returns($value, string $message)
{
    if ($value === false) {
        throw RuntimeException::withMessage($message);
    }

    return $value;
}
