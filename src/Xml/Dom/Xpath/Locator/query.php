<?php
declare(strict_types=1);

namespace VeeWee\Xml\Dom\Xpath\Locator;

use DOMXPath;
use function Psl\Fun\identity;
use function Psl\Fun\rethrow;
use function VeeWee\Xml\ErrorHandling\detect_warnings;

function query(string $query, \DOMNode $node = null): callable
{
    return static function (DOMXPath $xpath) use ($query, $node) {
        $node = $node ?? $xpath->document->documentElement;

        return detect_warnings(static fn () => $xpath->query($query, $node))->proceed(
            identity(),
            rethrow()
        );
    };
}
