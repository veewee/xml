<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Assert;

use Dom\NodeList;
use Psl\Type\Exception\AssertException;
use function Psl\Type\instance_of;

/**
 * @psalm-assert NodeList $node
 * @throws AssertException
 */
function assert_dom_node_list(mixed $node): NodeList
{
    return instance_of(NodeList::class)->assert($node);
}
