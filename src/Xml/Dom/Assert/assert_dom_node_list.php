<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Assert;

use DOMNodeList;
use Psl\Type\Exception\AssertException;
use function Psl\Type\instance_of;

/**
 * @psalm-assert DOMNodeList $node
 * @throws AssertException
 */
function assert_dom_node_list(mixed $node): DOMNodeList
{
    return instance_of(DOMNodeList::class)->assert($node);
}
