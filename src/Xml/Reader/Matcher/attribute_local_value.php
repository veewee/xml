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
function attribute_local_value(string $localName, string $value): Closure
{
    return static function (NodeSequence $sequence) use ($localName, $value): bool {
        return any(
            $sequence->current()->attributes(),
            static fn (AttributeNode $attribute): bool => $attribute->localName() === $localName && $attribute->value() === $value
        );
    };
}
