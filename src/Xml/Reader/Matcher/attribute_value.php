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
function attribute_value(string $name, string $value): Closure
{
    return static function (NodeSequence $sequence) use ($name, $value): bool {
        return any(
            $sequence->current()->attributes(),
            static fn (AttributeNode $attribute): bool => $attribute->name() === $name && $attribute->value() === $value
        );
    };
}
