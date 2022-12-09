<?php

declare(strict_types=1);

namespace VeeWee\Xml\ErrorHandling;

use Generator;

use Psl\Vec;
use VeeWee\Xml\Exception\RuntimeException;
use function libxml_clear_errors;
use function libxml_get_errors;
use function libxml_use_internal_errors;

/**
 * Additional logic needs to be placed on top of the detect_errors function if you want to lazily detect errors.
 * This method can be used while reading an \XMLReader and returning a Generator.
 * After every tick, this function will validate if issues were detected.
 *
 * @template Tr
 *
 * @psalm-param (callable(): bool) $tick
 * @psalm-param (callable(): ?Tr) $run
 * @psalm-return Generator<int, Tr, null, mixed>
 *
 * @psalm-suppress InvalidReturnType - Psalm gets lost here and thinks it returns "never"
 *
 * @throws RuntimeException
 */
function stop_on_first_issue(callable $tick, callable $run): Generator
{
    $previousErrorReporting = libxml_use_internal_errors(true);
    libxml_clear_errors();

    $detectIssues = static function (): void {
        $issues = issue_collection_from_xml_errors(Vec\values(libxml_get_errors()));
        if ($issues->count()) {
            throw RuntimeException::fromIssues('Detected issues during the parsing of the XML Stream', $issues);
        }
    };

    try {
        while ($tick()) {
            $result = $run();
            if ($result !== null) {
                yield $result;
            }

            $detectIssues();
        }

        $detectIssues();
    } finally {
        libxml_clear_errors();
        libxml_use_internal_errors($previousErrorReporting);
    }
}
