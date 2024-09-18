<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator;

use Closure;
use Dom\Element;
use Dom\XMLDocument;
use function VeeWee\Xml\Dom\Assert\assert_element;

/**
 * @return Closure(XMLDocument): Element
 */
function document_element(): Closure
{
    return static fn (XMLDocument $document): Element => assert_element($document->documentElement);
}
