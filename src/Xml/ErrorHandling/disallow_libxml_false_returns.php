<?php

declare(strict_types=1);

namespace VeeWee\Xml\ErrorHandling;

use Psl\Result\ResultInterface;
use VeeWee\Xml\Exception\RuntimeException;

/**
 * @template T
 * @param T $value
 * @return T
 *
 * @psalm-assert !false $value
 */
function disallow_libxml_false_returns($value, string $message): ResultInterface
{
    if ($value === false) {
        throw RuntimeException::withMessage($message);
    }

    return $value;
}
