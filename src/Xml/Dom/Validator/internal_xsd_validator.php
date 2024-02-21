<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Validator;

use Closure;
use \DOM\XMLDocument;
use VeeWee\Xml\ErrorHandling\Issue\IssueCollection;
use VeeWee\Xml\Xsd\Schema\Schema;
use VeeWee\Xml\Xsd\Schema\SchemaCollection;
use function VeeWee\Xml\Dom\Locator\Xsd\locate_all_xsd_schemas;
use function VeeWee\Xml\Internal\configure;

/**
 * @param list<callable(SchemaCollection): SchemaCollection> $schemaManipulators
 * @return Closure(\DOM\XMLDocument): IssueCollection
 */
function internal_xsd_validator(callable ... $schemaManipulators): Closure
{
    return static function (\DOM\XMLDocument $document) use ($schemaManipulators) : IssueCollection {
        $schemas = configure(...$schemaManipulators)(locate_all_xsd_schemas($document));

        return validator_chain(
            ...$schemas->map(
                /**
                 * @return Closure(\DOM\XMLDocument): IssueCollection
                 */
                static fn (Schema $schema): Closure => xsd_validator($schema->location())
            )
        )($document);
    };
}
