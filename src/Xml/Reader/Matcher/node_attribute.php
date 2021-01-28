<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader\Matcher;

use VeeWee\Xml\Reader\Node\AttributeNode;
use VeeWee\Xml\Reader\Node\NodeSequence;
use function Psl\Iter\any;

function node_attribute(string $key, string $value): callable
{
    return static function (NodeSequence $sequence) use ($key, $value): bool {
        return any(
            $sequence->current()->attributes,
            static fn (AttributeNode $attribute): bool => $attribute->name === $key && $attribute->value === $value
        );
    };
}
