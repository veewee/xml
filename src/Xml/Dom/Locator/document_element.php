<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator;

use Closure;
use DOMDocument;
use DOMElement;

/**
 * @return Closure(DOMDocument): DOMElement
 */
function document_element(): Closure
{
    return static fn (DOMDocument $document): DOMElement => $document->documentElement;
}
