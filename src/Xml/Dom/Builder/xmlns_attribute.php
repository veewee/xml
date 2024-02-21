<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use Closure;
use \DOM\Element;
use VeeWee\Xml\Xmlns\Xmlns;
use function VeeWee\Xml\Assertion\assert_strict_prefixed_name;

/**
 * @return Closure(\DOM\Element): \DOM\Element
 */
function xmlns_attribute(string $prefix, string $namespaceURI): Closure
{
    return static function (\DOM\Element $node) use ($prefix, $namespaceURI): \DOM\Element {
        $prefixed = 'xmlns:'.$prefix;
        assert_strict_prefixed_name($prefixed);

        $node->setAttributeNS(Xmlns::xmlns()->value(), $prefixed, $namespaceURI);

        return $node;
    };
}
