<?php

declare(strict_types=1);

namespace VeeWee\Xml\ErrorHandling;

use Exception;
use Psl\Result\ResultInterface;
use VeeWee\Xml\Exception\RuntimeException;

/**
 * @template T
 *
 * @param callable(): T $run
 *
 * @return ResultInterface<T>
 */
function disallow_issues(callable $run): ResultInterface
{
    [$result, $issues] = detect_errors($run);

    return $result->then(
        /**
         * @template T
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
         * @return no-return
         */
        static function (Exception $exception) use ($issues) {
            throw RuntimeException::combineExceptionWithIssues($exception, $issues);
        }
    );
}
