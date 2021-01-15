<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Xsd;

use DOMDocument;
use function Safe\preg_split;

/**
 * @return iterable<int, string>
 */
function locate_no_namespaced_xsd_schemas(DOMDocument $document): iterable
{
    $schemaNs = 'http://www.w3.org/2001/XMLSchema-instance';
    $attributes = $document->documentElement->attributes;

    if ($schemaLocNoNamespace = $attributes->getNamedItemNS($schemaNs, 'noNamespaceSchemaLocation')) {
        yield from preg_split('/\s+/', trim($schemaLocNoNamespace->textContent));
    }
}
