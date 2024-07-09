<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator;

use Closure;
use \Dom\XMLDocument;
use \Dom\Element;
use function VeeWee\Xml\Dom\Assert\assert_element;

/**
 * @return Closure(\Dom\XMLDocument): \Dom\Element
 */
function document_element(): Closure
{
    return static fn (\Dom\XMLDocument $document): \Dom\Element => assert_element($document->documentElement);
}
