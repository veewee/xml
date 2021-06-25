<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator;

use DOMDocument;

/**
 * @return callable(DOMDocument): ?string
 */
function root_namespace_uri(): callable
{
    return static fn (DOMDocument $document): ?string => $document->documentElement->namespaceURI;
}
