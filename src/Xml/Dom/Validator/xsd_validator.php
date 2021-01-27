<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Validator;

use DOMDocument;
use VeeWee\Xml\ErrorHandling\Issue\IssueCollection;
use function VeeWee\Xml\ErrorHandling\detect_issues;

/**
 * @return callable(DOMDocument): IssueCollection
 */
function xsd_validator(string $xsd): callable
{
    return static function (DOMDocument $document) use ($xsd): IssueCollection {
        [$_, $issues] = detect_issues(fn () => $document->schemaValidate($xsd));

        return $issues;
    };
}
