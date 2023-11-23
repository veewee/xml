<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use Closure;
use DOMDocument;
use VeeWee\Xml\ErrorHandling\Issue\Issue;
use VeeWee\Xml\ErrorHandling\Issue\IssueCollection;
use VeeWee\Xml\ErrorHandling\Issue\Level;
use VeeWee\Xml\Exception\RuntimeException;

/**
 * @param callable(DOMDocument): IssueCollection $validator
 *
 * @return Closure(DOMDocument): DOMDocument
 */
function validator(callable $validator, ?Level $minimumLevel = null): Closure
{
    $minimumLevel = $minimumLevel ?? Level::warning();

    return
        /**
         * @throws RuntimeException
         */
        static function (DOMDocument $document) use ($validator, $minimumLevel): DOMDocument {
            $issues = $validator($document)
                ->filter(static fn (Issue  $issue): bool => $issue->level()->value() >= $minimumLevel->value());

            if ($issues->count()) {
                throw RuntimeException::fromIssues('Invalid XML', $issues);
            }

            return $document;
        };
}
