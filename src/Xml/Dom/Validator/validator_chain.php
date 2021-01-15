<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Validator;

use DOMDocument;
use VeeWee\Xml\ErrorHandling\Issue\IssueCollection;
use function Psl\Iter\reduce;
use function VeeWee\Xml\Dom\Locator\Xsd\locate_all_xsd_schemas;
use function VeeWee\Xml\ErrorHandling\detect_errors;

/**
 * @return callable(DOMDocument): IssueCollection
 */
function validator_chain(callable ... $validators): callable
{
    return static function (DOMDocument $document) use ($validators) : IssueCollection {
        return reduce(
            $validators,
            static fn (IssueCollection $issues, $schema)
            => new IssueCollection(
                ...$issues->getIterator(),
                ...(xsd_validator($schema)($document))->getIterator()
            ),
            new IssueCollection()
        );
    };
}
