<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding\Internal;

use Exception;
use VeeWee\Xml\Encoding\Exception\EncodingException;

/**
 * @psalm-internal VeeWee\Xml\Encoding
 *
 * @template T
 * @param callable(): T $run
 * @return T
 *
 * @throws EncodingException
 */
function wrap_exception(callable $run)
{
    try {
        return $run();
    } catch (Exception $e) {
        throw EncodingException::wrapException($e);
    }
}
