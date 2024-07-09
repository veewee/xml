<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Assert;

use \Dom\NodeList;
use Psl\Type\Exception\AssertException;
use function Psl\Type\instance_of;

/**
 * @psalm-assert \Dom\NodeList $node
 * @throws AssertException
 */
function assert_dom_node_list(mixed $node): \Dom\NodeList
{
    return instance_of(\Dom\NodeList::class)->assert($node);
}
