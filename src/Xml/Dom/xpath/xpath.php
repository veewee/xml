<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\xpath;

use DOMDocument;
use DOMXPath;

/**
 * @param array<string, string> $namespaces
 */
function xpath(DOMDocument $document, array $namespaces): DOMXPath
{
    $xpath = new DOMXPath($document);
    foreach ($namespaces as $prefix => $namespaceURI) {
        $xpath->registerNamespace($prefix, $namespaceURI);
    }

    return $xpath;
}
