<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator;

use Closure;
use \DOM\XMLDocument;
use \DOM\Element;
use function VeeWee\Xml\Dom\Assert\assert_element;

/**
 * @return Closure(\DOM\XMLDocument): \DOM\Element
 */
function document_element(): Closure
{
    return static fn (\DOM\XMLDocument $document): \DOM\Element => assert_element($document->documentElement);
}
