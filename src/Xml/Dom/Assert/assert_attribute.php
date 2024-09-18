<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Assert;

use Dom\Attr;
use Dom\Element;
use Psl\Type\Exception\AssertException;
use function Psl\Type\instance_of;

/**
 * @psalm-assert Element $node
 * @throws AssertException
 */
function assert_attribute(mixed $node): Attr
{
    return instance_of(Attr::class)->assert($node);
}
