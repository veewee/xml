<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Validator;

use DOMDocument;
use VeeWee\Xml\ErrorHandling\Issue\IssueCollection;
use VeeWee\Xml\Xsd\Schema;
use VeeWee\Xml\Xsd\SchemaCollection;
use function Psl\Fun\pipe;
use function VeeWee\Xml\Dom\Locator\Xsd\locate_all_xsd_schemas;

/**
 * @param list<callable(SchemaCollection): SchemaCollection> $schemaManipulators
 * @return callable(DOMDocument): IssueCollection
 */
function internal_xsd_validator(callable ... $schemaManipulators): callable
{
    return static function (DOMDocument $document) use ($schemaManipulators) : IssueCollection {
        $schemas = pipe(...$schemaManipulators)(locate_all_xsd_schemas($document));

        return validator_chain(
            ...$schemas->map(
                /**
                 * @return callable(DOMDocument): IssueCollection
                 */
                static fn (Schema $schema): callable => xsd_validator($schema->location())
            )
        )($document);
    };
}
