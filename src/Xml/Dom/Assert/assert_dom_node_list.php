<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Assert;

use \DOM\NodeList;
use Psl\Type\Exception\AssertException;
use function Psl\Type\instance_of;

/**
 * @psalm-assert \DOM\NodeList $node
 * @throws AssertException
 */
function assert_dom_node_list(mixed $node): \DOM\NodeList
{
    return instance_of(\DOM\NodeList::class)->assert($node);
}
