<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Validator;

use Closure;
use \Dom\XMLDocument;
use VeeWee\Xml\ErrorHandling\Issue\IssueCollection;
use function Psl\Iter\reduce;

/**
 * @param list<callable(\Dom\XMLDocument): IssueCollection> $validators
 * @return Closure(\Dom\XMLDocument): IssueCollection
 */
function validator_chain(callable ... $validators): Closure
{
    return static fn (\Dom\XMLDocument $document): IssueCollection =>
        reduce(
            $validators,
            /**
             * @param callable(\Dom\XMLDocument): IssueCollection $validator
             */
            static fn (IssueCollection $issues, callable $validator): IssueCollection
                => new IssueCollection(
                    ...$issues->getIterator(),
                    ...$validator($document)->getIterator()
                ),
            new IssueCollection()
        );
}
