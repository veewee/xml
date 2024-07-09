<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator;

use Closure;
use \Dom\XMLDocument;

/**
 * @return Closure(\Dom\XMLDocument): ?string
 */
function root_namespace_uri(): Closure
{
    return static fn (\Dom\XMLDocument $document): ?string => document_element()($document)->namespaceURI;
}
