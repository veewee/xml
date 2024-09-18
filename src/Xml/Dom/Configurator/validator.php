<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use Closure;
use Dom\XMLDocument;
use VeeWee\Xml\ErrorHandling\Issue\Issue;
use VeeWee\Xml\ErrorHandling\Issue\IssueCollection;
use VeeWee\Xml\ErrorHandling\Issue\Level;
use VeeWee\Xml\Exception\RuntimeException;

/**
 * @param callable(XMLDocument): IssueCollection $validator
 *
 * @return Closure(XMLDocument): XMLDocument
 */
function validator(callable $validator, ?Level $minimumLevel = null): Closure
{
    $minimumLevel = $minimumLevel ?? Level::warning();

    return
        /**
         * @throws RuntimeException
         */
        static function (XMLDocument $document) use ($validator, $minimumLevel): XMLDocument {
            $issues = $validator($document)
                ->filter(static fn (Issue  $issue): bool => $issue->level()->value() >= $minimumLevel->value());

            if ($issues->count()) {
                throw RuntimeException::fromIssues('Invalid XML', $issues);
            }

            return $document;
        };
}
