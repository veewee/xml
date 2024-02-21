<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Validator;

use Closure;
use \DOM\XMLDocument;
use VeeWee\Xml\ErrorHandling\Issue\IssueCollection;
use function Psl\Iter\reduce;

/**
 * @param list<callable(\DOM\XMLDocument): IssueCollection> $validators
 * @return Closure(\DOM\XMLDocument): IssueCollection
 */
function validator_chain(callable ... $validators): Closure
{
    return static fn (\DOM\XMLDocument $document): IssueCollection =>
        reduce(
            $validators,
            /**
             * @param callable(\DOM\XMLDocument): IssueCollection $validator
             */
            static fn (IssueCollection $issues, callable $validator): IssueCollection
                => new IssueCollection(
                    ...$issues->getIterator(),
                    ...$validator($document)->getIterator()
                ),
            new IssueCollection()
        );
}
