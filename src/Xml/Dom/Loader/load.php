<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Loader;

use Exception;
use Psl\Result\ResultInterface;
use VeeWee\Xml\Exception\RuntimeException;

use function VeeWee\Xml\ErrorHandling\detect_errors;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

/**
 * @param callable(): bool $loader
 * @return ResultInterface<true>
 */
function load(callable $loader): ResultInterface
{
    [$result, $issues] = detect_errors(static fn (): bool => $loader());

    return $result->then(
        /**
         * @return true
         */
        static fn(bool $result): bool => disallow_libxml_false_returns(
            $result && !$issues->count(),
            'Could not load the DOM Document'
        ),
        static function (Exception $exception) use ($issues) {
            throw RuntimeException::combineExceptionWithIssues($exception, $issues);
        }
    );
}
