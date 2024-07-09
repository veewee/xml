<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Assert;

use \Dom\Element;
use Psl\Type\Exception\AssertException;
use function Psl\Type\instance_of;

/**
 * @psalm-assert \Dom\Element $node
 * @throws AssertException
 */
function assert_element(mixed $node): \Dom\Element
{
    return instance_of(\Dom\Element::class)->assert($node);
}
