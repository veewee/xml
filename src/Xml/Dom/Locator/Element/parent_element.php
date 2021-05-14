<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Element;

use DOMElement;
use DOMNode;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Predicate\is_element;

/**
 * @throws RuntimeException
 */
function parent_element(DOMNode $child): DOMElement
{
    $parent = $child->parentNode;
    if (!$parent|| !is_element($parent)) {
        throw RuntimeException::withMessage('Can not find parent element for '.$child::class.' '.$child->nodeName);
    }

    return $parent;
}
