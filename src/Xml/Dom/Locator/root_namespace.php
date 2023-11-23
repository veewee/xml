<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator;

use Closure;
use DOMDocument;

/**
 * @return Closure(DOMDocument): ?string
 */
function root_namespace_uri(): Closure
{
    return static fn (DOMDocument $document): ?string => $document->documentElement->namespaceURI;
}
