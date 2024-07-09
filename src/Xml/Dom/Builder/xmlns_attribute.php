<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use Closure;
use \Dom\Element;
use VeeWee\Xml\Xmlns\Xmlns;
use function VeeWee\Xml\Assertion\assert_strict_prefixed_name;

/**
 * @return Closure(\Dom\Element): \Dom\Element
 */
function xmlns_attribute(string $prefix, string $namespaceURI): Closure
{
    return static function (\Dom\Element $node) use ($prefix, $namespaceURI): \Dom\Element {
        $prefixed = 'xmlns:'.$prefix;
        assert_strict_prefixed_name($prefixed);

        $node->setAttributeNS(Xmlns::xmlns()->value(), $prefixed, $namespaceURI);

        return $node;
    };
}
