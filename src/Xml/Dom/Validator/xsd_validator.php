<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Validator;

use Closure;
use \Dom\XMLDocument;
use VeeWee\Xml\ErrorHandling\Issue\IssueCollection;
use function VeeWee\Xml\ErrorHandling\detect_issues;

/**
 * @return Closure(\Dom\XMLDocument): IssueCollection
 */
function xsd_validator(string $xsd): Closure
{
    return static function (\Dom\XMLDocument $document) use ($xsd): IssueCollection {
        [$_, $issues] = detect_issues(static fn () => $document->schemaValidate($xsd));

        return $issues;
    };
}
