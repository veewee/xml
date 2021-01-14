<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Loader;

use Exception;
use Psl\Result\ResultInterface;
use VeeWee\Xml\Exception\RuntimeException;

use function VeeWee\Xml\ErrorHandling\detect_errors;

/**
 * @param callable(DOMDocument): bool $loader
 * @return ResultInterface<true>
 */
function load(callable $loader): ResultInterface
{
    [$result, $issues] = detect_errors($loader);

    return $result->then(
        static function (bool $loaded) use ($issues) : bool {
            if (!$loaded) {
                throw RuntimeException::fromIssues('Could not load the DOM Document', $issues);
            }

            return true;
        },
        static function (Exception $exception) use ($issues) {
            throw RuntimeException::combineExceptionWithIssues($exception, $issues);
        }
    );
}
