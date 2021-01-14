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

function detect_warnings(callable $run): ResultInterface
{
    $issues = new MutableVector([]);
    $errorHandler = static function ($errno, $errstr = '', $errfile = '', $errline = 0) use ($issues) {
        $issues->add(
            new Issue(Level::warning(), $errno, 0, $errstr, $errfile, $errline)
        );
    };

    set_error_handler($errorHandler, E_WARNING);
    $result = wrap($run);
    restore_error_handler();

    return $result->then(
        static function ($result) use ($issues) {
            if ($issues->count()) {
                throw RuntimeException::fromIssues('Detected warnings', new IssueCollection(...$issues->toArray()));
            }

            return $result;
        },
        rethrow()
    );
}
