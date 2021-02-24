<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator;

use DOMDocument;
use DOMElement;

/**
 * @return callable(DOMDocument): DOMElement
 */
function document_element(): callable
{
    return static fn (DOMDocument $document): DOMElement => $document->documentElement;
}
