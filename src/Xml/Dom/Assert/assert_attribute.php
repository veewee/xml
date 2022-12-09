<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Assert;

use DOMAttr;
use Psl\Type\Exception\AssertException;
use function Psl\Type\instance_of;

/**
 * @psalm-assert DOMElement $node
 * @throws AssertException
 */
function assert_attribute(mixed $node): DOMAttr
{
    return instance_of(DOMAttr::class)->assert($node);
}
