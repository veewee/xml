<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Predicate;

function is_prefixed_node_name(string $nodeName): bool
{
    return (bool)preg_match('/^[^:]+:[^:]+$/', $nodeName);
}
