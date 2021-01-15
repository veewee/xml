<?php
declare(strict_types=1);

namespace VeeWee\Xml\Dom\Xpath\Locator;

use DOMXPath;
use function VeeWee\Xml\ErrorHandling\detect_warnings;

/**
 * @return callable(DOMXPath): mixed
 */
function evaluate(string $query, \DOMNode $node = null): callable
{
    return static function (DOMXPath $xpath) use ($query, $node) {
        $node = $node ?? $xpath->document->documentElement;

        return detect_warnings(static fn () => $xpath->evaluate($query, $node))->getResult();
    };
}
