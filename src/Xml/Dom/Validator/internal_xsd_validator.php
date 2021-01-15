<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Validator;

use DOMDocument;
use VeeWee\Xml\ErrorHandling\Issue\IssueCollection;
use function Psl\Arr\map;
use function VeeWee\Xml\Dom\Locator\Xsd\locate_all_xsd_schemas;

/**
 * @return callable(DOMDocument): IssueCollection
 */
function internal_xsd_validator(): callable
{
    return static function (DOMDocument $document): IssueCollection {
        return validator_chain(
            ...map(
                locate_all_xsd_schemas($document),
                static fn (string $schema): callable => xsd_validator($schema)
            )
        )($document);
    };
}
