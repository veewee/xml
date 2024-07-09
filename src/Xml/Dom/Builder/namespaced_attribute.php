<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use Closure;
use \Dom\Element;
use function VeeWee\Xml\Assertion\assert_strict_prefixed_name;

/**
 * @return Closure(\Dom\Element): \Dom\Element
 */
function namespaced_attribute(string $namespace, string $qualifiedName, string $value): Closure
{
    return static function (\Dom\Element $node) use ($namespace, $qualifiedName, $value): \Dom\Element {
        assert_strict_prefixed_name($qualifiedName);

        $node->setAttributeNS($namespace, $qualifiedName, $value);

        return $node;
    };
}
