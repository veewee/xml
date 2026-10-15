<?php

declare(strict_types=1);

namespace VeeWee\Xml\ErrorHandling;

use Throwable;
use VeeWee\Xml\Exception\RuntimeException;
use function count;
use function libxml_clear_errors;
use function libxml_get_errors;
use function libxml_use_internal_errors;

/**
 * Does the same as detect_issues(), without building a Result or an IssueCollection when libxml reported nothing.
 * Every Writer::write() and every DOM load runs through here, so the happy path has to stay cheap.
 *
 * @template T
 *
 * @param callable(): T $run
 *
 * @throws RuntimeException
 * @return T
 */
function disallow_issues(callable $run)
{
    $previousErrorReporting = libxml_use_internal_errors(true);
    libxml_clear_errors();

    try {
        $value = $run();
    } catch (Throwable $exception) {
        $errors = libxml_get_errors();
        libxml_clear_errors();
        libxml_use_internal_errors($previousErrorReporting);

        throw RuntimeException::combineExceptionWithIssues($exception, issue_collection_from_xml_errors($errors));
    }

    $errors = libxml_get_errors();
    libxml_clear_errors();
    libxml_use_internal_errors($previousErrorReporting);

    if ($errors !== []) {
        $issues = issue_collection_from_xml_errors($errors);
        if (count($issues)) {
            throw RuntimeException::fromIssues('XML issues detected: ', $issues);
        }
    }

    return $value;
}
