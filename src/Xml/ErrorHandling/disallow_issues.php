<?php

declare(strict_types=1);

namespace VeeWee\Xml\ErrorHandling;

use Exception;
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
        static function (Exception $exception) use ($issues) {
            throw RuntimeException::combineExceptionWithIssues($exception, $issues);
        }
    );
}
