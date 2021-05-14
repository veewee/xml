<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use DOMElement;
use VeeWee\Xml\Xmlns\Xmlns;
use function VeeWee\Xml\Assertion\assert_strict_prefixed_name;

/**
 * @param non-emtpy-string $prefix
 * @param non-emtpy-string $namespaceURI
 * @return callable(DOMElement): DOMElement
 */
function xmlns_attribute(string $prefix, string $namespaceURI): callable
{
    return static function (DOMElement $node) use ($prefix, $namespaceURI): DOMElement {
        $prefixed = 'xmlns:'.$prefix;
        assert_strict_prefixed_name($prefixed);

        $node->setAttributeNS(Xmlns::xmlns()->value(), $prefixed, $namespaceURI);

        return $node;
    };
}
