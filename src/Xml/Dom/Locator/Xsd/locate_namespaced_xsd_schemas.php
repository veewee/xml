<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Xsd;

use DOMDocument;
use function Safe\preg_split;

/**
 * @return iterable<int, string>
 */
function locate_namespaced_xsd_schemas(DOMDocument $document): iterable
{
    $schemaNs = 'http://www.w3.org/2001/XMLSchema-instance';
    $attributes = $document->documentElement->attributes;

    if ($schemaLocation = $attributes->getNamedItemNS($schemaNs, 'schemaLocation')) {
        /** @var list<string> $parts */
        $parts = preg_split('/\s+/', trim($schemaLocation->textContent));
        foreach ($parts as $key => $value) {
            if ($key & 1) {
                yield $value;
            }
        }
    }
}
