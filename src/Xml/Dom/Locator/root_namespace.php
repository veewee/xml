<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator;

use Closure;
use \DOM\XMLDocument;

/**
 * @return Closure(\DOM\XMLDocument): ?string
 */
function root_namespace_uri(): Closure
{
    return static fn (\DOM\XMLDocument $document): ?string => $document->documentElement->namespaceURI;
}
