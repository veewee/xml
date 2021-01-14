<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Validator;

use DOMDocument;
use Psl\Result\ResultInterface;

/**
 * @return callable(): ResultInterface<true>
 */
function xsd_validator(string $xsd): callable
{
    return static function (DOMDocument $document) use ($xsd): ResultInterface {
        return validate(static fn(): bool => $document->schemaValidate($xsd));
    };
}
