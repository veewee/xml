<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader\Matcher;

use Closure;
use VeeWee\Xml\Reader\Node\AttributeNode;
use VeeWee\Xml\Reader\Node\NodeSequence;
use function Psl\Iter\any;

/**
 * @return Closure(NodeSequence): bool
 */
function namespaced_attribute_value(string $namespace, string $localName, string $value): Closure
{
    return static function (NodeSequence $sequence) use ($namespace, $localName, $value): bool {
        return any(
            $sequence->current()->attributes(),
            static fn (AttributeNode $attribute): bool =>
                $attribute->localName() === $localName
                && $attribute->namespace() === $namespace
                && $attribute->value() === $value
        );
    };
}
