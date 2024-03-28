<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Assert;

use \DOM\Element;
use Psl\Type\Exception\AssertException;
use function Psl\Type\instance_of;

/**
 * @psalm-assert \DOM\Element $node
 * @throws AssertException
 */
function assert_element(mixed $node): \DOM\Element
{
    return instance_of(\DOM\Element::class)->assert($node);
}
