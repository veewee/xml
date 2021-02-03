<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Validator;

use DOMDocument;
use VeeWee\Xml\ErrorHandling\Issue\IssueCollection;
use function Psl\Iter\reduce;

/**
 * @param list<callable(DOMDocument): IssueCollection> $validators
 * @return callable(DOMDocument): IssueCollection
 */
function validator_chain(callable ... $validators): callable
{
    return static fn (DOMDocument $document): IssueCollection =>
        reduce(
            $validators,
            /**
             * @param callable(DOMDocument): IssueCollection $validator
             */
            static fn (IssueCollection $issues, $validator): IssueCollection
                => new IssueCollection(
                    ...$issues->getIterator(),
                    ...$validator($document)->getIterator()
                ),
            new IssueCollection()
        );
}
