<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding\Internal;

use Closure;
use Exception;
use VeeWee\Xml\Encoding\Exception\EncodingException;

/**
 * @psalm-internal VeeWee\Xml\Encoding
 *
 * @template T
 * @param Closure(): T $run
 * @return T
 *
 * @throws EncodingException
 */
function wrap_exception(Closure $run)
{
    try {
        return $run();
    } catch (Exception $e) {
        throw EncodingException::wrapException($e);
    }
}
