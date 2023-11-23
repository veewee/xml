<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Validator;

use Closure;
use DOMDocument;
use VeeWee\Xml\ErrorHandling\Issue\IssueCollection;
use function VeeWee\Xml\ErrorHandling\detect_issues;

/**
 * @return Closure(DOMDocument): IssueCollection
 */
function xsd_validator(string $xsd): Closure
{
    return static function (DOMDocument $document) use ($xsd): IssueCollection {
        [$_, $issues] = detect_issues(static fn () => $document->schemaValidate($xsd));

        return $issues;
    };
}
