<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Validator;

use Closure;
use DOMDocument;
use VeeWee\Xml\ErrorHandling\Issue\IssueCollection;
use function Psl\Iter\reduce;

/**
 * @param list<callable(DOMDocument): IssueCollection> $validators
 * @return Closure(DOMDocument): IssueCollection
 */
function validator_chain(callable ... $validators): Closure
{
    return static fn (DOMDocument $document): IssueCollection =>
        reduce(
            $validators,
            /**
             * @param callable(DOMDocument): IssueCollection $validator
             */
            static fn (IssueCollection $issues, callable $validator): IssueCollection
                => new IssueCollection(
                    ...$issues->getIterator(),
                    ...$validator($document)->getIterator()
                ),
            new IssueCollection()
        );
}
