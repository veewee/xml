<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Assert;

use \DOM\Attr;
use Psl\Type\Exception\AssertException;
use function Psl\Type\instance_of;

/**
 * @psalm-assert \DOM\Element $node
 * @throws AssertException
 */
function assert_attribute(mixed $node): \DOM\Attr
{
    return instance_of(\DOM\Attr::class)->assert($node);
}
