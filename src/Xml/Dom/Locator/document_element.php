<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator;

use Closure;
use \DOM\XMLDocument;
use \DOM\Element;

/**
 * @return Closure(\DOM\XMLDocument): \DOM\Element
 */
function document_element(): Closure
{
    return static fn (\DOM\XMLDocument $document): \DOM\Element => $document->documentElement;
}
