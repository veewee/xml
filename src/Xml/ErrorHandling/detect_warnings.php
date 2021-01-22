<?php

declare(strict_types=1);

namespace VeeWee\Xml\ErrorHandling;

use Psl\Collection\MutableVector;
use Psl\Result\ResultInterface;
use VeeWee\Xml\ErrorHandling\Issue\Issue;
use VeeWee\Xml\ErrorHandling\Issue\IssueCollection;
use VeeWee\Xml\ErrorHandling\Issue\Level;
use VeeWee\Xml\Exception\RuntimeException;
use function Psl\Fun\rethrow;
use function Psl\Result\wrap;

/**
 * @template T
 * @param callable(): T $run
 *
 * @return ResultInterface<T>
 */
function detect_warnings(callable $run): ResultInterface
{
    $issues = new MutableVector([]);
    /**
     * @var (callable(int, string, string=, int=, array=): never-return) $errorHandler
     */
    $errorHandler = static function (
        int $errNo,
        string $errMessage,
        string $errFile = '',
        int $errLine = 0,
        array $context = []
    ) use ($issues): void {
        $issues->add(
            new Issue(Level::warning(), $errNo, 0, $errMessage, $errFile, $errLine)
        );
    };

    set_error_handler($errorHandler, E_WARNING);
    $result = wrap($run);
    restore_error_handler();

    return $result->then(
        /**
         * @template T
         * @param T $result
         * @return T
         */
        static function ($result) use ($issues) {
            if ($issues->count()) {
                throw RuntimeException::fromIssues('Detected warnings', new IssueCollection(...$issues->toArray()));
            }

            return $result;
        },
        rethrow()
    );
}
