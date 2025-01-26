<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use Closure;
use Dom\Element;
use VeeWee\Xml\Xmlns\Xmlns;

/**
 * @return Closure(Element): Element
 */
function default_xmlns_attribute(string $namespaceURI): Closure
{
    return static function (Element $node) use ($namespaceURI): Element {
        $node->setAttributeNS(Xmlns::xmlns()->value(), 'xmlns', $namespaceURI);

        return $node;
    };
}
