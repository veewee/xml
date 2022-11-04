<?php

declare(strict_types=1);

namespace VeeWee\Xml\ErrorHandling;

use Throwable;
use VeeWee\Xml\Exception\RuntimeException;

/**
 * @template T
 *
 * @param callable(): T $run
 *
 * @throws RuntimeException
 * @return T
 */
function disallow_issues(callable $run)
{
    [$result, $issues] = detect_issues($run);

    /** @psalm-suppress ArgumentTypeCoercion - Psalm does not support generic closures... */
    return $result->proceed(
        /**
         * @param T $value
         * @return T
         */
        static function ($value) use ($issues) {
            if (count($issues)) {
                throw RuntimeException::fromIssues('XML issues detected: ', $issues);
            }

            return $value;
        },
        /**
         * @throws RuntimeException
         * @return never
         */
        static function (Throwable $exception) use ($issues) {
            throw RuntimeException::combineExceptionWithIssues($exception, $issues);
        }
    );
}
