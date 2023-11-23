<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader\Matcher;

use Closure;
use VeeWee\Xml\Reader\Node\AttributeNode;
use VeeWee\Xml\Reader\Node\NodeSequence;
use function Psl\Iter\any;

/**
 * @deprecated Use attribute_value instead! This will be removed in next major version
 * @return Closure(NodeSequence): bool
 */
function node_attribute(string $key, string $value): Closure
{
    return static function (NodeSequence $sequence) use ($key, $value): bool {
        return any(
            $sequence->current()->attributes(),
            static fn (AttributeNode $attribute): bool => $attribute->name() === $key && $attribute->value() === $value
        );
    };
}
