<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Assert;

use \Dom\Attr;
use Psl\Type\Exception\AssertException;
use function Psl\Type\instance_of;

/**
 * @psalm-assert \Dom\Element $node
 * @throws AssertException
 */
function assert_attribute(mixed $node): \Dom\Attr
{
    return instance_of(\Dom\Attr::class)->assert($node);
}
