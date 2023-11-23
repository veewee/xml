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
function attribute_local_name(string $localName): Closure
{
    return static function (NodeSequence $sequence) use ($localName): bool {
        return any(
            $sequence->current()->attributes(),
            static fn (AttributeNode $attribute): bool => $attribute->localName() === $localName
        );
    };
}
